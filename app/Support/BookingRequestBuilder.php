<?php

namespace App\Support;

final class BookingRequestBuilder
{
    public function __construct(private readonly BookingPricingCatalog $pricingCatalog) {}

    /**
     * @return array<string, mixed>
     */
    public function newService(?string $service, ?string $variant): array
    {
        $selectionMode = BookingRequestSchema::serviceSelectionMode($service);
        $details = $selectionMode === 'multiple' ? ['care_needs' => []] : [];

        if ($selectionMode === 'multiple' && filled($variant)) {
            $details['care_needs'] = [$variant];
            $variant = null;
        }

        if ($selectionMode === 'pet_types') {
            $variant = null;
        }

        if ($service === 'relocation') {
            $details = BookingRequestSchema::relocationDetails($variant, $details);
        }

        return [
            'service_key' => $service,
            'service_variant' => $variant,
            'assigned_pet_ids' => [],
            'requested_date' => null,
            'requested_time' => null,
            'location' => null,
            'details' => $details,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function newPet(): array
    {
        return [
            'name' => null,
            'species' => null,
            'size' => null,
            'breed' => null,
            'age' => null,
            'sex' => null,
            'notes' => null,
            'details' => [],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @return array<int, array<string, mixed>>
     */
    public function changeService(array $services, int $index, ?string $service): array
    {
        if (! array_key_exists($index, $services)) {
            return $services;
        }

        $services[$index] = $this->newService($service, null);

        return $services;
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<string, mixed>
     */
    public function changeVariant(array $service, ?string $variant): array
    {
        $serviceKey = (string) ($service['service_key'] ?? '');

        if (BookingRequestSchema::serviceSelectionMode($serviceKey) !== 'single') {
            return $service;
        }

        $variantOptions = BookingRequestSchema::variantOptions($serviceKey);
        $service['service_variant'] = array_key_exists((string) $variant, $variantOptions) ? $variant : null;
        $service['assigned_pet_ids'] = [];

        if ($serviceKey === 'relocation') {
            $service['details'] = BookingRequestSchema::relocationDetails(
                $service['service_variant'],
                $service['details'] ?? [],
            );
        }

        return $service;
    }

    /**
     * @param  array<int, array<string, mixed>>  $pets
     * @param  array<int, array<string, mixed>>  $services
     * @return array{pets: array<int, array<string, mixed>>, services: array<int, array<string, mixed>>, focus_index: int}
     */
    public function removePet(array $pets, array $services, int $index): array
    {
        if (! array_key_exists($index, $pets)) {
            return [
                'pets' => $pets,
                'services' => $services,
                'focus_index' => max(0, count($pets) - 1),
            ];
        }

        unset($pets[$index]);
        $pets = array_values($pets);

        foreach ($services as &$service) {
            $assignedPetIds = [];

            foreach ($service['assigned_pet_ids'] ?? [] as $petId) {
                $petId = (int) $petId;

                if ($petId === $index) {
                    continue;
                }

                $assignedPetIds[] = $petId > $index ? $petId - 1 : $petId;
            }

            $service['assigned_pet_ids'] = array_values(array_unique($assignedPetIds));
        }
        unset($service);

        return [
            'pets' => $pets,
            'services' => $services,
            'focus_index' => min($index, count($pets) - 1),
        ];
    }

    /**
     * @param  array<string, mixed>  $service
     */
    public function serviceNeedsSelection(array $service): bool
    {
        $serviceKey = $service['service_key'] ?? null;
        $selectionMode = BookingRequestSchema::serviceSelectionMode($serviceKey);

        return match ($selectionMode) {
            'multiple' => $this->pricingCatalog->careNeeds($service['details']['care_needs'] ?? []) === [],
            'single' => $this->pricingCatalog->serviceOptionRequired($serviceKey)
                && empty($service['service_variant']),
            default => false,
        };
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     * @param  array<int, array<string, mixed>>  $pets
     * @return list<int>
     */
    public function assignedPetIndexes(array $services, array $pets): array
    {
        $assignedPetIndexes = [];

        foreach ($services as $service) {
            foreach ($service['assigned_pet_ids'] ?? [] as $petIndex) {
                $petIndex = (int) $petIndex;

                if (array_key_exists($petIndex, $pets)) {
                    $assignedPetIndexes[$petIndex] = true;
                }
            }
        }

        $assignedPetIndexes = array_keys($assignedPetIndexes);
        sort($assignedPetIndexes);

        return array_map('intval', $assignedPetIndexes);
    }

    /**
     * @param  array<int, array<string, mixed>>  $services
     */
    public function petAssignedServiceCount(array $services, int $petIndex): int
    {
        return collect($services)
            ->filter(fn (array $service): bool => in_array($petIndex, array_map('intval', $service['assigned_pet_ids'] ?? []), true))
            ->count();
    }
}
