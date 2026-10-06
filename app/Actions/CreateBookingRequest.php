<?php

namespace App\Actions;

use App\Mail\NewBookingRequest;
use App\Models\BookingRequest;
use App\Models\BusinessProfile;
use App\Support\BookingPricingCatalog;
use App\Support\BookingRequestIntake;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateBookingRequest
{
    public function __construct(
        private readonly BookingPricingCatalog $pricingCatalog,
        private readonly BookingRequestIntake $intake,
    ) {}

    /**
     * @param array{
     *     contact: array<string, mixed>,
     *     pets: array<int, array<string, mixed>>,
     *     services: array<int, array<string, mixed>>,
     *     idempotency_key?: ?string,
     *     source?: ?string,
     *     context?: array<string, mixed>
     * } $data
     */
    public function handle(array $data): BookingRequest
    {
        $data = $this->intake->validate($data);
        $pets = array_values($data['pets']);
        $services = array_values($data['services']);

        $bookingRequest = DB::transaction(function () use ($data, $pets, $services): BookingRequest {
            $contact = $data['contact'];
            $primaryPet = $pets[0] ?? [];
            $primaryService = $services[0] ?? [];
            $phone = $contact['phone'];
            $idempotencyKey = trim((string) ($data['idempotency_key'] ?? ''));
            $idempotencyKey = $idempotencyKey !== '' ? $idempotencyKey : null;

            if ($idempotencyKey !== null && preg_match('/^[A-Za-z0-9._:-]{1,64}$/', $idempotencyKey) !== 1) {
                throw ValidationException::withMessages([
                    'idempotency_key' => 'This request key is invalid.',
                ]);
            }

            $idempotencyHash = $idempotencyKey === null
                ? null
                : $this->idempotencyHash($contact, $pets, $services, $data);
            $details = $primaryService['details'] ?? [];

            $attributes = [
                'name' => $contact['name'],
                'email' => $contact['email'],
                'phone' => $phone,
                'preferred_contact_method' => $contact['preferred_contact_method'] ?? null,
                'service_key' => $primaryService['service_key'],
                'service_variant' => $primaryService['service_variant'] ?? null,
                'pricing_tier' => $primaryService['pricing_tier'] ?? null,
                'source' => $data['source'] ?? null,
                'context' => [
                    'schema_version' => 1,
                    ...($data['context'] ?? []),
                ],
                'requested_date' => $primaryService['requested_date'] ?? $details['check_in'] ?? null,
                'requested_time' => $primaryService['requested_time'] ?? null,
                'pet_name' => $primaryPet['name'],
                'pet_type' => $primaryPet['species'],
                'location' => $primaryService['location'] ?? $details['pickup'] ?? null,
                'message' => $details['message'] ?? null,
                'idempotency_key' => $idempotencyKey,
                'idempotency_hash' => $idempotencyHash,
            ];

            if ($idempotencyKey === null) {
                $bookingRequest = BookingRequest::create($attributes);
            } else {
                $bookingRequest = BookingRequest::firstOrCreate(
                    ['idempotency_key' => $idempotencyKey],
                    $attributes,
                );

                if (! $bookingRequest->wasRecentlyCreated) {
                    if ($bookingRequest->idempotency_hash !== $idempotencyHash) {
                        throw ValidationException::withMessages([
                            'idempotency_key' => 'This request key was already used for different booking details.',
                        ]);
                    }

                    return $bookingRequest->load(['pets', 'services']);
                }
            }

            $petModels = [];

            foreach ($pets as $petIndex => $pet) {
                $petDetails = Arr::wrap($pet['details'] ?? []);

                if (($pet['size'] ?? null) !== null) {
                    $petDetails['size'] = $pet['size'];
                }

                $petModels[$petIndex] = $bookingRequest->pets()->create([
                    'name' => $pet['name'],
                    'species' => $pet['species'],
                    'breed' => $pet['breed'] ?? null,
                    'age' => $pet['age'] ?? null,
                    'sex' => $pet['sex'] ?? null,
                    'notes' => $pet['notes'] ?? null,
                    'details' => $petDetails,
                ]);
            }

            foreach ($services as $service) {
                $details = $service['details'] ?? [];
                $servicePetIndexes = array_values(array_filter(
                    array_map(static fn (mixed $index): int => (int) $index, $service['assigned_pet_ids'] ?? []),
                    static fn (int $index): bool => array_key_exists($index, $pets),
                ));
                $requestedDate = $service['requested_date'] ?? $details['check_in'] ?? null;
                $requestedEndDate = $service['requested_end_date'] ?? $details['check_out'] ?? null;
                $assignedPets = array_map(function (int $index) use ($petModels): array {
                    $pet = $petModels[$index];

                    return [
                        'name' => $pet->name,
                        'species' => $pet->species,
                        'breed' => $pet->breed,
                        'age' => $pet->age,
                        'sex' => $pet->sex,
                        'size' => data_get($pet->details, 'size'),
                        'details' => $pet->details ?? [],
                    ];
                }, $servicePetIndexes);
                $pricingSnapshot = $this->pricingCatalog->quoteForService([
                    ...$service,
                    'requested_date' => $requestedDate,
                    'requested_end_date' => $requestedEndDate,
                    'requested_time' => $service['requested_time'] ?? null,
                    'location' => $service['location'] ?? null,
                    'details' => $details,
                ], $assignedPets);

                $bookingService = $bookingRequest->services()->create([
                    'service_key' => $service['service_key'],
                    'service_variant' => $service['service_variant'] ?? null,
                    'pricing_tier' => $service['pricing_tier'] ?? null,
                    'requested_date' => $requestedDate,
                    'requested_end_date' => $requestedEndDate,
                    'requested_time' => $service['requested_time'] ?? null,
                    'location' => $service['location'] ?? null,
                    'details' => $details,
                    'quote_amount' => null,
                    'quote_currency' => $this->pricingCatalog->currency(),
                    'price_snapshot' => $pricingSnapshot,
                ]);

                $bookingService->pets()->attach(array_map(
                    static fn (int|string $index): string => $petModels[(int) $index]->getKey(),
                    $servicePetIndexes,
                ));
            }

            return $bookingRequest->load(['pets', 'services']);
        });

        if ($bookingRequest->wasRecentlyCreated) {
            $this->notifyStaff($bookingRequest);
        }

        return $bookingRequest;
    }

    private function notifyStaff(BookingRequest $bookingRequest): void
    {
        $recipient = BusinessProfile::query()->value('primary_email');

        if (! is_string($recipient) || ! filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        try {
            Mail::to($recipient)->queue(new NewBookingRequest($bookingRequest));
        } catch (Throwable $exception) {
            Log::warning('Booking request notification could not be queued.', [
                'booking_request_id' => $bookingRequest->getKey(),
                'exception' => $exception,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $contact
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     * @param  array<string, mixed>  $data
     */
    private function idempotencyHash(array $contact, array $pets, array $services, array $data): string
    {
        return hash('sha256', serialize([
            'contact' => [
                'name' => $contact['name'] ?? null,
                'email' => $contact['email'] ?? null,
                'phone' => $contact['phone'] ?? null,
                'preferred_contact_method' => $contact['preferred_contact_method'] ?? null,
            ],
            'pets' => $pets,
            'services' => $services,
            'source' => $data['source'] ?? null,
            'context' => $data['context'] ?? null,
        ]));
    }
}
