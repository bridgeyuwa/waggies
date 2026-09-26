<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Support\BookingPricingCatalog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateBookingRequest
{
    public function __construct(private readonly BookingPricingCatalog $pricingCatalog) {}

    /**
     * @param array{
     *     contact: array<string, mixed>,
     *     pets: array<int, array<string, mixed>>,
     *     services: array<int, array<string, mixed>>,
     *     source?: ?string,
     *     context?: array<string, mixed>
     * } $data
     */
    public function handle(array $data): BookingRequest
    {
        $this->validateAssignments($data['pets'], $data['services']);

        return DB::transaction(function () use ($data): BookingRequest {
            $pricingCatalog = $this->pricingCatalog;
            $contact = $data['contact'];
            $pets = array_values($data['pets']);
            $services = array_values($data['services']);
            $primaryPet = $pets[0] ?? [];
            $primaryService = $services[0] ?? [];
            $details = Arr::wrap($primaryService['details'] ?? []);

            $bookingRequest = BookingRequest::create([
                'name' => $contact['name'],
                'email' => $contact['email'],
                'phone' => $contact['phone'],
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
            ]);

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
                $details = Arr::wrap($service['details'] ?? []);
                $servicePetIndexes = array_values(array_filter(
                    array_map(static fn (mixed $index): int => (int) $index, $service['assigned_pet_ids'] ?? []),
                    static fn (int $index): bool => array_key_exists($index, $pets),
                ));
                $servicePets = array_map(static fn (int|string $index): array => $pets[(int) $index], $servicePetIndexes);
                $priceSnapshot = $pricingCatalog->quoteForService($service, $servicePets);
                $requestedDate = $service['requested_date'] ?? $details['check_in'] ?? null;
                $requestedEndDate = $service['requested_end_date'] ?? $details['check_out'] ?? null;
                $quoteAmount = in_array($priceSnapshot['status'] ?? null, ['fixed', 'estimate'], true)
                    ? ($priceSnapshot['amount'] ?? null)
                    : null;

                $bookingService = $bookingRequest->services()->create([
                    'service_key' => $service['service_key'],
                    'service_variant' => $service['service_variant'] ?? null,
                    'pricing_tier' => $service['pricing_tier'] ?? null,
                    'requested_date' => $requestedDate,
                    'requested_end_date' => $requestedEndDate,
                    'requested_time' => $service['requested_time'] ?? null,
                    'location' => $service['location'] ?? null,
                    'details' => $details,
                    'quote_amount' => $quoteAmount,
                    'quote_currency' => config('waggies_pricing.currency', 'NGN'),
                    'price_snapshot' => $priceSnapshot,
                ]);

                $bookingService->pets()->attach(array_map(
                    static fn (int|string $index): string => $petModels[(int) $index]->getKey(),
                    $servicePetIndexes,
                ));
            }

            return $bookingRequest->load(['pets', 'services']);
        });
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

            foreach ($petIndexes as $petIndex) {
                $petIndex = (int) $petIndex;

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
}
