<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class BookingPricingCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function services(bool $availableOnly = false, string $channel = 'booking'): array
    {
        $services = config('waggies_pricing.services', []);

        if (! $availableOnly) {
            return $services;
        }

        return array_filter(
            $services,
            fn (array $service): bool => $this->isAvailable($service, $channel),
        );
    }

    /**
     * @return array<string, string>
     */
    public function serviceOptions(bool $availableOnly = false, string $channel = 'booking'): array
    {
        return collect($this->services($availableOnly, $channel))
            ->mapWithKeys(static fn (array $service, string $key): array => [
                $key => $service['label'] ?? Str::headline($key),
            ])
            ->all();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function variants(string $service, bool $availableOnly = false, string $channel = 'booking'): array
    {
        $definition = $this->services()[$service] ?? [];

        if ($availableOnly && ! $this->isAvailable($definition, $channel)) {
            return [];
        }

        $variants = $definition['variants'] ?? [];

        if (! $availableOnly) {
            return $variants;
        }

        return array_filter(
            $variants,
            fn (array $variant): bool => $this->isAvailable($variant, $channel),
        );
    }

    /**
     * @return array<string, string>
     */
    public function variantOptions(?string $service, bool $availableOnly = false, string $channel = 'booking'): array
    {
        if ($service === null || $service === '') {
            return [];
        }

        return collect($this->variants($service, $availableOnly, $channel))
            ->mapWithKeys(static fn (array $variant, string $key): array => [
                $key => $variant['label'] ?? Str::headline($key),
            ])
            ->all();
    }

    public function selectionMode(?string $service): string
    {
        return (string) ($this->services()[$service ?? '']['selection_mode'] ?? 'single');
    }

    /**
     * @return array<string, string>
     */
    public function careNeedOptions(bool $availableOnly = false, string $channel = 'booking'): array
    {
        return $this->variantOptions('vet-care', $availableOnly, $channel);
    }

    /**
     * @return list<string>
     */
    public function careNeeds(mixed $value, bool $availableOnly = true, string $channel = 'booking'): array
    {
        if (! is_array($value)) {
            return [];
        }

        $options = $this->careNeedOptions($availableOnly, $channel);

        return collect(array_values($value))
            ->filter(static fn (mixed $need): bool => is_string($need))
            ->unique()
            ->filter(static fn (string $need): bool => array_key_exists($need, $options))
            ->values()
            ->all();
    }

    public function careNeedsAreValid(mixed $value, bool $availableOnly = true, string $channel = 'booking'): bool
    {
        if (! is_array($value) || $value === []) {
            return false;
        }

        $values = array_values($value);

        if (count(array_filter($values, 'is_string')) !== count($values)) {
            return false;
        }

        if (count(array_unique($values)) !== count($values)) {
            return false;
        }

        return count($this->careNeeds($values, $availableOnly, $channel)) === count($values);
    }

    public function serviceOptionRequired(?string $service): bool
    {
        return $this->selectionMode($service) === 'single'
            && $this->variantOptions($service, availableOnly: true) !== [];
    }

    /**
     * @param  array<string, mixed>  $service
     * @param  array<int, array<string, mixed>>  $pets
     */
    public function serviceSummary(array $service, array $pets = []): string
    {
        $serviceKey = (string) ($service['service_key'] ?? '');
        $serviceLabel = $this->serviceOptions()[$serviceKey] ?? Str::headline($serviceKey);
        $selectionLabel = $this->serviceSelectionSummary($service, $pets);

        return implode(' · ', array_filter([$serviceLabel, $selectionLabel]));
    }

    /**
     * @param  array<string, mixed>  $service
     * @param  array<int, array<string, mixed>>  $pets
     */
    public function serviceSelectionSummary(array $service, array $pets = []): ?string
    {
        $serviceKey = (string) ($service['service_key'] ?? '');
        $details = is_array($service['details'] ?? null) ? $service['details'] : [];

        if ($serviceKey === 'boarding') {
            $species = collect(['dog', 'cat'])
                ->filter(fn (string $species): bool => collect($pets)->contains(fn (array $pet): bool => ($pet['species'] ?? null) === $species))
                ->map(fn (string $species): string => $species === 'dog' ? 'Dogs' : 'Cats')
                ->values();

            if ($species->isNotEmpty()) {
                return $species->join(' and ');
            }
        }

        if ($serviceKey === 'vet-care') {
            $careNeeds = $this->careNeeds($details['care_needs'] ?? []);

            if ($careNeeds === [] && filled($service['service_variant'] ?? null)) {
                $careNeeds = [$service['service_variant']];
            }

            $labels = collect($careNeeds)
                ->map(fn (string $need): ?string => $this->careNeedOptions()[$need] ?? null)
                ->filter()
                ->values();

            if ($labels->isNotEmpty()) {
                return $labels->join(' · ');
            }
        }

        $variant = $service['service_variant'] ?? null;

        return is_string($variant)
            ? $this->variantOptions($serviceKey)[$variant] ?? null
            : null;
    }

    /**
     * @return list<string>|null
     */
    public function allowedPetTypes(string $service, ?string $variant = null): ?array
    {
        $definition = $this->services()[$service] ?? null;

        if ($definition === null) {
            return null;
        }

        if ($variant !== null && $variant !== '') {
            $variantDefinition = $this->variants($service)[$variant] ?? null;

            if (is_array($variantDefinition) && isset($variantDefinition['pet_types'])) {
                return array_values($variantDefinition['pet_types']);
            }
        }

        return isset($definition['pet_types']) ? array_values($definition['pet_types']) : null;
    }

    public function isPetCompatible(string $service, ?string $variant, ?string $petType): bool
    {
        if ($petType === null || $petType === '') {
            return true;
        }

        $allowedPetTypes = $this->allowedPetTypes($service, $variant);

        return $allowedPetTypes === null || in_array($petType, $allowedPetTypes, true);
    }

    public function petCompatibilityReason(string $service, ?string $variant, ?string $petType): ?string
    {
        if ($this->isPetCompatible($service, $variant, $petType)) {
            return null;
        }

        $labels = ['dog' => 'dogs', 'cat' => 'cats'];
        $allowedLabel = collect($this->allowedPetTypes($service, $variant) ?? [])
            ->map(fn (string $type): string => $labels[$type] ?? $type)
            ->join(' or ');

        return "Only {$allowedLabel} can be assigned to this service.";
    }

    public function requiresPetWeight(string $service, ?string $variant): bool
    {
        return false;
    }

    public function requiresPetSize(string $service, ?string $variant): bool
    {
        return $service === 'boarding'
            && $this->pricingVariant($service, $variant, 'dog') === 'dogs'
            && $this->sizeRates($service, 'dogs') !== [];
    }

    /**
     * @return array<string, array{label: string, examples: string|null, guidance: string|null, manual_review: bool}>
     */
    public function sizeOptions(string $service, ?string $variant): array
    {
        return collect($this->sizeRates($service, $variant))
            ->mapWithKeys(static fn (array $rate, string $key): array => [
                $key => [
                    'label' => $rate['booking_label'] ?? $rate['label'] ?? Str::headline($key),
                    'examples' => $rate['examples'] ?? null,
                    'guidance' => $rate['guidance'] ?? null,
                    'manual_review' => (bool) ($rate['manual_review'] ?? false),
                ],
            ])
            ->all();
    }

    /**
     * Packages and tiers are intentionally not part of the active catalogue.
     * The legacy methods remain as empty compatibility contracts for old callers.
     *
     * @return array<string, array<string, mixed>>
     */
    public function tiers(?string $service, ?string $variant = null, bool $availableOnly = false, string $channel = 'booking'): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public function tierOptions(?string $service, ?string $variant = null, bool $availableOnly = false, string $channel = 'booking'): array
    {
        return [];
    }

    public function isAvailable(array $definition, string $channel = 'booking'): bool
    {
        if (($definition['enabled'] ?? true) === false) {
            return false;
        }

        return match ($channel) {
            'pricing' => ($definition['pricing_enabled'] ?? true) !== false,
            default => ($definition['booking_enabled'] ?? true) !== false,
        };
    }

    public function priceLabel(string $service, ?string $variant, array $definition): string
    {
        if (($definition['type'] ?? null) === 'quote' || ($definition['manual_review'] ?? false)) {
            return 'Staff review required';
        }

        $amount = $definition['amount'] ?? null;
        $maximum = $definition['max_amount'] ?? $amount;

        if (! is_numeric($amount)) {
            return 'Request review';
        }

        $amount = (int) $amount;
        $maximum = is_numeric($maximum) ? (int) $maximum : $amount;

        return $amount === $maximum
            ? $this->formatAmount($amount)
            : $this->formatAmount($amount).'–'.$this->formatAmount($maximum);
    }

    public function tierDescription(array $tier): ?string
    {
        return null;
    }

    /**
     * Return an indicative draft only. Staff quotation remains authoritative.
     *
     * @param  array<string, mixed>  $pet
     * @return array<string, mixed>
     */
    public function quote(string $service, ?string $variant, ?string $legacyTier, array $pet = [], int $quantity = 1, string $channel = 'booking'): array
    {
        $serviceDefinition = $this->services()[$service] ?? null;

        if ($serviceDefinition === null || ! $this->isAvailable($serviceDefinition, $channel)) {
            return ['status' => 'unavailable', 'reason' => 'This service is temporarily unavailable.'];
        }

        $variantDefinitions = $this->variants($service);
        $effectiveVariant = $this->pricingVariant($service, $variant, $pet['species'] ?? null);

        if ($variantDefinitions !== []) {
            if ($this->selectionMode($service) === 'multiple') {
                if ($variant === null || ! array_key_exists($variant, $variantDefinitions)) {
                    return ['status' => 'needs_input', 'reason' => 'Choose a service option.'];
                }

                $effectiveVariant = $variant;
            }

            if ($effectiveVariant === null || ! array_key_exists($effectiveVariant, $variantDefinitions)) {
                return ['status' => 'needs_input', 'reason' => 'Choose a service option.'];
            }

            if (! $this->isAvailable($variantDefinitions[$effectiveVariant], $channel)) {
                return ['status' => 'unavailable', 'reason' => 'This service option is temporarily unavailable.'];
            }
        }

        if (! $this->isPetCompatible($service, $effectiveVariant, $pet['species'] ?? null)) {
            return [
                'status' => 'unavailable',
                'reason' => $this->petCompatibilityReason($service, $effectiveVariant, $pet['species'] ?? null),
            ];
        }

        $variantDefinition = $variantDefinitions[$effectiveVariant ?? ''] ?? $serviceDefinition;

        if ($service === 'boarding' && $effectiveVariant === 'dogs') {
            return $this->dogBoardingQuote($serviceDefinition, $variantDefinition, $pet, $quantity);
        }

        if ($service === 'boarding' && $effectiveVariant === 'cats') {
            return [
                'status' => 'quote',
                'type' => 'quote',
                'reason' => $variantDefinition['pricing_note'] ?? 'Cat boarding rate requires staff confirmation.',
                'currency' => config('waggies_pricing.currency', 'NGN'),
            ];
        }

        if (($variantDefinition['type'] ?? 'quote') === 'quote') {
            return [
                'status' => 'quote',
                'type' => 'quote',
                'reason' => $variantDefinition['description'] ?? 'Waggies will review this request and confirm the final quote.',
                'currency' => config('waggies_pricing.currency', 'NGN'),
            ];
        }

        $amount = (int) ($variantDefinition['amount'] ?? 0) * max(1, $quantity);
        $maximum = (int) ($variantDefinition['max_amount'] ?? $variantDefinition['amount'] ?? 0) * max(1, $quantity);

        return [
            'status' => 'estimate',
            'type' => 'estimate',
            'amount' => $amount,
            'max_amount' => $maximum,
            'currency' => config('waggies_pricing.currency', 'NGN'),
            'unit' => $serviceDefinition['unit'] ?? null,
        ];
    }

    /**
     * @param  array<string, mixed>  $service
     * @param  array<int, array<string, mixed>>  $pets
     * @return array<string, mixed>
     */
    public function quoteForService(array $service, array $pets, string $channel = 'booking'): array
    {
        $serviceKey = (string) ($service['service_key'] ?? '');
        $variant = $service['service_variant'] ?? null;
        $details = is_array($service['details'] ?? null) ? $service['details'] : [];
        $quantity = $this->boardingNights($serviceKey, $details);

        if ($serviceKey === 'vet-care' && $variant === null) {
            return $this->veterinaryQuoteForService($details, $pets, $channel);
        }

        $lines = [];

        foreach ($pets as $pet) {
            $quote = $this->quote($serviceKey, $variant, null, $pet, $quantity, $channel);

            if (in_array($quote['status'] ?? null, ['unavailable', 'needs_input'], true)) {
                return [
                    ...$quote,
                    'authority' => 'staff_quotation',
                    'draft' => true,
                    'lines' => $lines,
                    'nights' => $quantity,
                ];
            }

            $lines[] = [
                'pet_name' => $pet['name'] ?? null,
                'status' => $quote['status'],
                'amount' => $quote['amount'] ?? null,
                'max_amount' => $quote['max_amount'] ?? null,
                'size' => $quote['size'] ?? null,
            ];
        }

        if ($lines === []) {
            return [
                'status' => 'needs_input',
                'reason' => 'Assign at least one pet to this service.',
                'authority' => 'staff_quotation',
                'draft' => true,
                'lines' => [],
                'nights' => $quantity,
            ];
        }

        if (collect($lines)->contains(fn (array $line): bool => $line['status'] === 'quote')) {
            return [
                'status' => 'quote',
                'type' => 'quote',
                'authority' => 'staff_quotation',
                'draft' => true,
                'lines' => $lines,
                'nights' => $quantity,
                'currency' => config('waggies_pricing.currency', 'NGN'),
            ];
        }

        $amount = (int) collect($lines)->sum('amount');
        $maximum = (int) collect($lines)->sum('max_amount');
        $discount = $this->multiplePetDiscount($serviceKey, $lines);
        $status = collect($lines)->contains(fn (array $line): bool => $line['status'] === 'estimate') ? 'estimate' : 'fixed';

        return [
            'status' => $status,
            'type' => $status,
            'authority' => 'staff_quotation',
            'draft' => true,
            'amount' => $amount,
            'max_amount' => $maximum,
            'subtotal' => $amount,
            'max_subtotal' => $maximum,
            'discount' => $discount,
            'discount_authority' => 'manual_quotation',
            'lines' => $lines,
            'nights' => $quantity,
            'currency' => config('waggies_pricing.currency', 'NGN'),
        ];
    }

    /**
     * @param  array<string, mixed>  $details
     * @param  array<int, array<string, mixed>>  $pets
     * @return array<string, mixed>
     */
    private function veterinaryQuoteForService(array $details, array $pets, string $channel): array
    {
        $careNeeds = $details['care_needs'] ?? [];

        if (! $this->careNeedsAreValid($careNeeds, true, $channel)) {
            return [
                'status' => 'needs_input',
                'reason' => 'Choose at least one veterinary care need.',
                'authority' => 'staff_quotation',
                'draft' => true,
                'lines' => [],
                'nights' => 1,
            ];
        }

        if ($pets === []) {
            return [
                'status' => 'needs_input',
                'reason' => 'Assign at least one pet to this service.',
                'authority' => 'staff_quotation',
                'draft' => true,
                'lines' => [],
                'nights' => 1,
            ];
        }

        foreach ($pets as $pet) {
            if ($this->isPetCompatible('vet-care', null, $pet['species'] ?? null)) {
                continue;
            }

            return [
                'status' => 'unavailable',
                'reason' => $this->petCompatibilityReason('vet-care', null, $pet['species'] ?? null),
                'authority' => 'staff_quotation',
                'draft' => true,
                'lines' => [],
                'nights' => 1,
            ];
        }

        return [
            'status' => 'quote',
            'type' => 'quote',
            'reason' => 'The veterinary team will review the selected care needs and confirm the final quote.',
            'authority' => 'staff_quotation',
            'draft' => true,
            'care_needs' => $careNeeds,
            'lines' => collect($pets)
                ->map(fn (array $pet): array => [
                    'pet_name' => $pet['name'] ?? null,
                    'status' => 'quote',
                    'amount' => null,
                    'max_amount' => null,
                    'size' => null,
                ])
                ->all(),
            'nights' => 1,
            'currency' => config('waggies_pricing.currency', 'NGN'),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function sizeRates(string $service, ?string $variant): array
    {
        $variant ??= $service === 'boarding' ? 'dogs' : null;

        return $this->services()[$service]['variants'][$variant]['size_rates'] ?? [];
    }

    private function dogBoardingQuote(array $serviceDefinition, array $variantDefinition, array $pet, int $quantity): array
    {
        $size = $pet['size'] ?? null;
        $sizeRate = $this->sizeRates('boarding', 'dogs')[$size] ?? null;

        if ($sizeRate === null) {
            return [
                'status' => 'needs_input',
                'reason' => 'Choose your dog\'s size so we can prepare an indicative boarding estimate.',
            ];
        }

        if (($sizeRate['manual_review'] ?? false) === true) {
            return [
                'status' => 'quote',
                'type' => 'quote',
                'size' => $size,
                'reason' => $sizeRate['guidance'] ?? 'This dog requires manual boarding review.',
                'currency' => config('waggies_pricing.currency', 'NGN'),
            ];
        }

        $multiplier = max(1, $quantity);

        return [
            'status' => 'estimate',
            'type' => 'estimate',
            'amount' => ((int) $sizeRate['amount']) * $multiplier,
            'max_amount' => ((int) ($sizeRate['max_amount'] ?? $sizeRate['amount'])) * $multiplier,
            'currency' => config('waggies_pricing.currency', 'NGN'),
            'unit' => $serviceDefinition['unit'] ?? null,
            'size' => $size,
        ];
    }

    private function boardingNights(string $service, array $details): int
    {
        if ($service !== 'boarding' || empty($details['check_in']) || empty($details['check_out'])) {
            return 1;
        }

        return max(1, (int) Carbon::parse($details['check_in'])->diffInDays(Carbon::parse($details['check_out'])));
    }

    /**
     * @param  array<int, array<string, mixed>>  $lines
     * @return array{amount: int, max_amount: int, percentage: int, base_amount: int, max_base_amount: int}
     */
    private function multiplePetDiscount(string $service, array $lines): array
    {
        $discount = config('waggies_pricing.discounts.multiple_pet', []);
        $percentage = (int) ($discount['percentage'] ?? 0);
        $appliesFrom = (int) ($discount['applies_from_pet'] ?? 2);
        $petCount = count($lines);

        if (($discount['enabled'] ?? false) !== true
            || ! in_array($service, $discount['applies_to'] ?? [], true)
            || $petCount < $appliesFrom
        ) {
            return [
                'amount' => 0,
                'max_amount' => 0,
                'percentage' => 0,
                'base_amount' => 0,
                'max_base_amount' => 0,
            ];
        }

        $discountedLines = array_slice($lines, max(0, $appliesFrom - 1));
        $baseAmount = (int) collect($discountedLines)->sum('amount');
        $maxBaseAmount = (int) collect($discountedLines)->sum('max_amount');

        return [
            'amount' => (int) round($baseAmount * $percentage / 100),
            'max_amount' => (int) round($maxBaseAmount * $percentage / 100),
            'percentage' => $percentage,
            'base_amount' => $baseAmount,
            'max_base_amount' => $maxBaseAmount,
        ];
    }

    private function formatAmount(int $amount): string
    {
        return '₦'.number_format($amount);
    }

    private function pricingVariant(string $service, ?string $variant, ?string $petType): ?string
    {
        if (filled($variant)) {
            return $variant;
        }

        return $service === 'boarding'
            ? match ($petType) {
                'dog' => 'dogs',
                'cat' => 'cats',
                default => null,
            }
        : null;
    }
}
