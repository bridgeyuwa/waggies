<?php

namespace App\Actions;

use App\Models\BookingRequest;
use App\Support\BookingPricingCatalog;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateBookingRequest
{
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
        return DB::transaction(function () use ($data): BookingRequest {
            $pricingCatalog = app(BookingPricingCatalog::class);
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
                $petModels[$petIndex] = $bookingRequest->pets()->create([
                    'name' => $pet['name'],
                    'species' => $pet['species'],
                    'breed' => $pet['breed'] ?? null,
                    'age' => $pet['age'] ?? null,
                    'sex' => $pet['sex'] ?? null,
                    'notes' => $pet['notes'] ?? null,
                    'details' => $pet['details'] ?? [],
                ]);
            }

            foreach ($services as $service) {
                $details = Arr::wrap($service['details'] ?? []);
                $servicePetIndexes = array_key_exists('assigned_pet_ids', $service)
                    ? array_values(array_filter($service['assigned_pet_ids'], static fn (mixed $index): bool => array_key_exists((int) $index, $pets)))
                    : array_keys($pets);
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
}
