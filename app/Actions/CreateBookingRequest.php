<?php

namespace App\Actions;

use App\Mail\NewBookingRequest;
use App\Models\BookingRequest;
use App\Models\BusinessProfile;
use App\Support\BookingPricingCatalog;
use App\Support\PhoneNumber;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Throwable;

class CreateBookingRequest
{
    public function __construct(private readonly BookingPricingCatalog $pricingCatalog) {}

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
        $pets = array_values($data['pets']);
        $services = array_values($data['services']);

        $this->validateServiceSelections($services);
        $this->validateAssignments($pets, $services);

        $bookingRequest = DB::transaction(function () use ($data, $pets, $services): BookingRequest {
            $contact = $data['contact'];
            $primaryPet = $pets[0] ?? [];
            $primaryService = $services[0] ?? [];
            $phone = PhoneNumber::normalizeContact($contact);

            if ($phone === null) {
                throw ValidationException::withMessages([
                    'contact.phone_number' => 'Enter a valid phone number for the selected country.',
                ]);
            }

            $contact['phone'] = $phone;
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
            $details = $this->serviceDetails($primaryService);

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
                'requested_date' => $primaryService['requested_date'] ?? $details['check_in'] ?? now()->toDateString(),
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
                $details = $this->serviceDetails($service);
                $servicePetIndexes = array_values(array_filter(
                    array_map(static fn (mixed $index): int => (int) $index, $service['assigned_pet_ids'] ?? []),
                    static fn (int $index): bool => array_key_exists($index, $pets),
                ));
                $requestedDate = $service['requested_date'] ?? $details['check_in'] ?? null;
                $requestedEndDate = $service['requested_end_date'] ?? $details['check_out'] ?? null;

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
                    'quote_currency' => config('waggies_pricing.currency', 'NGN'),
                    'price_snapshot' => null,
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

    /**
     * @param  array<int, array<string, mixed>>  $services
     */
    private function validateServiceSelections(array $services): void
    {
        $messages = [];
        $catalogue = $this->pricingCatalog->services();

        foreach ($services as $serviceIndex => $service) {
            $serviceKey = (string) ($service['service_key'] ?? '');
            $serviceDefinition = $catalogue[$serviceKey] ?? null;

            if (! is_array($serviceDefinition) || ! $this->pricingCatalog->isAvailable($serviceDefinition)) {
                $messages["services.{$serviceIndex}.service_key"] = 'Choose an available service.';

                continue;
            }

            $variantOptions = $this->pricingCatalog->variantOptions($serviceKey, availableOnly: true);
            $variant = $service['service_variant'] ?? null;
            $selectionMode = $this->pricingCatalog->selectionMode($serviceKey);

            if ($this->pricingCatalog->serviceOptionRequired($serviceKey)
                && (! is_string($variant) || ! array_key_exists($variant, $variantOptions))) {
                $messages["services.{$serviceIndex}.service_variant"] = 'Choose an active service option.';

                continue;
            }

            if (in_array($selectionMode, ['pet_types', 'multiple'], true)
                && filled($variant)
                && (! is_string($variant) || ! array_key_exists($variant, $variantOptions))) {
                $messages["services.{$serviceIndex}.service_variant"] = 'Choose a valid service option.';

                continue;
            }

            if ($selectionMode !== 'multiple') {
                continue;
            }

            $details = $this->serviceDetails($service);

            if (! $this->pricingCatalog->careNeedsAreValid($details['care_needs'] ?? [])) {
                $messages["services.{$serviceIndex}.details.care_needs"] = 'Choose at least one veterinary care need.';
            }
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<string, mixed>
     */
    private function serviceDetails(array $service): array
    {
        $details = Arr::wrap($service['details'] ?? []);

        if (($service['service_key'] ?? null) === 'boarding') {
            unset($details['emergency_contact_secondary']);
        }

        if (($service['service_key'] ?? null) === 'vet-care'
            && empty($details['care_needs'])
            && is_string($service['service_variant'] ?? null)
            && $service['service_variant'] !== '') {
            $details['care_needs'] = [$service['service_variant']];
        }

        return $details;
    }

    /**
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     */
    private function validateAssignments(array $pets, array $services): void
    {
        $messages = [];
        $assignedPetIndexes = [];

        foreach ($services as $serviceIndex => $service) {
            $petIndexes = $service['assigned_pet_ids'] ?? [];

            if (! is_array($petIndexes) || $petIndexes === []) {
                $messages["services.{$serviceIndex}.assigned_pet_ids"] = 'Assign at least one compatible pet to this service.';

                continue;
            }

            $normalisedPetIndexes = [];

            foreach ($petIndexes as $petIndex) {
                if (! $this->isValidPetIndex($petIndex)) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"] = 'Choose a pet that exists in this request.';

                    continue;
                }

                $petIndex = (int) $petIndex;

                if (in_array($petIndex, $normalisedPetIndexes, true)) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"] = 'Choose each pet only once for this service.';

                    continue;
                }

                $normalisedPetIndexes[] = $petIndex;

                if (! array_key_exists($petIndex, $pets)) {
                    $messages["services.{$serviceIndex}.assigned_pet_ids"] = 'Choose a pet that exists in this request.';

                    continue;
                }

                $assignedPetIndexes[$petIndex] = true;

                if ($this->pricingCatalog->isPetCompatible(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                    $pets[$petIndex]['species'] ?? null,
                )) {
                    continue;
                }

                $messages["services.{$serviceIndex}.assigned_pet_ids"] = $this->pricingCatalog->petCompatibilityReason(
                    (string) ($service['service_key'] ?? ''),
                    $service['service_variant'] ?? null,
                    $pets[$petIndex]['species'] ?? null,
                ) ?? 'Choose a pet that matches this service.';
            }
        }

        foreach ($pets as $petIndex => $pet) {
            if (isset($assignedPetIndexes[$petIndex])) {
                continue;
            }

            $messages["pets.{$petIndex}.assignments"] = 'Assign this pet to at least one service or remove it.';
        }

        if ($messages !== []) {
            throw ValidationException::withMessages($messages);
        }
    }

    private function isValidPetIndex(mixed $petIndex): bool
    {
        return (is_int($petIndex) && $petIndex >= 0)
            || (is_string($petIndex) && ctype_digit($petIndex));
    }
}
