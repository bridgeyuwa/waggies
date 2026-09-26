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

        $variants = $definition['variants'] ?? $definition['pricing']['pet_variants'] ?? [];

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

    /**
     * @return array<string, array<string, mixed>>
     */
    public function tiers(?string $service, ?string $variant = null, bool $availableOnly = false, string $channel = 'booking'): array
    {
        if ($service === null || $service === '') {
            return [];
        }

        $definition = $this->services()[$service] ?? [];

        if ($availableOnly && ! $this->isAvailable($definition, $channel)) {
            return [];
        }

        $tiers = $definition['tiers'] ?? [];

        if ($variant !== null && $variant !== '') {
            $variantDefinition = $this->variants($service)[$variant] ?? [];
            $tiers = $variantDefinition['tiers'] ?? $tiers;

            if (($definition['pricing']['pet_variants'][$variant]['tiers'] ?? null) !== null) {
                $baseTiers = $definition['tiers'] ?? [];
                $variantTiers = $definition['pricing']['pet_variants'][$variant]['tiers'];
                $tiers = [];

                foreach ($baseTiers as $tierKey => $baseTier) {
                    $variantTier = $variantTiers[$tierKey] ?? [];
                    $tier = array_replace_recursive($baseTier, $variantTier);

                    if (($baseTier['enabled'] ?? true) === false || ($variantTier['enabled'] ?? true) === false) {
                        $tier['enabled'] = false;
                    }

                    $tiers[$tierKey] = $tier;
                }
            }
        }

        if (! $availableOnly) {
            return $tiers;
        }

        return array_filter(
            $tiers,
            fn (array $tier): bool => $this->isAvailable($tier, $channel),
        );
    }

    /**
     * @return array<string, string>
     */
    public function tierOptions(?string $service, ?string $variant = null, bool $availableOnly = false, string $channel = 'booking'): array
    {
        return collect($this->tiers($service, $variant, $availableOnly, $channel))
            ->mapWithKeys(static fn (array $tier, string $key): array => [
                $key => $tier['label'] ?? Str::headline($key),
            ])
            ->all();
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

    public function priceLabel(string $service, ?string $variant, array $tier): string
    {
        if (($tier['type'] ?? 'fixed') === 'quote') {
            return 'Custom quote';
        }

        $rates = $this->sizeRates($service, $variant);

        if ($rates !== []) {
            $adjustment = (int) ($tier['adjustment'] ?? 0);
            $minimum = collect($rates)->min(fn (array $rate): int => (int) $rate['amount']) + $adjustment;
            $maximum = collect($rates)->max(fn (array $rate): int => (int) $rate['max_amount']) + $adjustment;

            return sprintf('From %s–%s', $this->formatAmount($minimum), $this->formatAmount($maximum));
        }

        $amount = (int) ($tier['amount'] ?? 0);
        $maximum = (int) ($tier['max_amount'] ?? $amount);

        if ($amount === $maximum) {
            return $this->formatAmount($amount);
        }

        return sprintf('%s–%s', $this->formatAmount($amount), $this->formatAmount($maximum));
    }

    /**
     * @param  array<string, mixed>  $pet
     * @return array<string, mixed>
     */
    public function quote(string $service, ?string $variant, ?string $tier, array $pet = [], int $quantity = 1, string $channel = 'booking'): array
    {
        $serviceDefinition = $this->services()[$service] ?? null;

        if ($serviceDefinition === null || ! $this->isAvailable($serviceDefinition, $channel)) {
            return ['status' => 'unavailable', 'reason' => 'This service is temporarily unavailable.'];
        }

        $variantDefinitions = $this->variants($service);

        if ($variantDefinitions !== []) {
            if ($variant === null || ! array_key_exists($variant, $variantDefinitions)) {
                return ['status' => 'unavailable', 'reason' => 'Choose the pet type for this service.'];
            }

            if (! $this->isAvailable($variantDefinitions[$variant], $channel)) {
                return ['status' => 'unavailable', 'reason' => 'This pet type is temporarily unavailable.'];
            }
        } elseif ($variant !== null && $variant !== '') {
            return ['status' => 'unavailable', 'reason' => 'This service does not use a pet type selection.'];
        }

        $tierDefinition = $this->tiers($service, $variant)[$tier ?? ''] ?? null;

        if ($tierDefinition === null || ! $this->isAvailable($tierDefinition, $channel)) {
            return ['status' => 'unavailable', 'reason' => 'This package is temporarily unavailable.'];
        }

        if ($this->sizeRates($service, $variant) !== []) {
            return $this->sizeBasedQuote($service, $variant, $tierDefinition, $pet, $quantity);
        }

        if (($tierDefinition['type'] ?? 'fixed') === 'quote') {
            return [
                'status' => 'quote',
                'type' => 'quote',
                'currency' => config('waggies_pricing.currency', 'NGN'),
            ];
        }

        $amount = (int) ($tierDefinition['amount'] ?? 0) * max(1, $quantity);
        $maximum = (int) ($tierDefinition['max_amount'] ?? $tierDefinition['amount'] ?? 0) * max(1, $quantity);

        return [
            'status' => ($tierDefinition['type'] ?? 'fixed') === 'estimate' ? 'estimate' : 'fixed',
            'type' => $tierDefinition['type'] ?? 'fixed',
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
        $tier = $service['pricing_tier'] ?? null;
        $details = $service['details'] ?? [];
        $quantity = $this->boardingNights($serviceKey, $details);
        $lines = [];

        foreach ($pets as $pet) {
            $quote = $this->quote($serviceKey, $variant, $tier, $pet, $quantity, $channel);

            if (in_array($quote['status'] ?? null, ['unavailable', 'needs_input'], true)) {
                return [
                    ...$quote,
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
                'weight_kg' => $quote['weight_kg'] ?? ($pet['weight_kg'] ?? null),
            ];
        }

        if ($lines === []) {
            return [
                'status' => 'needs_input',
                'reason' => 'Assign at least one pet to this service.',
                'lines' => [],
                'nights' => $quantity,
            ];
        }

        if (collect($lines)->contains(fn (array $line): bool => $line['status'] === 'quote')) {
            return [
                'status' => 'quote',
                'type' => 'quote',
                'lines' => $lines,
                'nights' => $quantity,
                'currency' => config('waggies_pricing.currency', 'NGN'),
            ];
        }

        $amount = (int) collect($lines)->sum('amount');
        $maximum = (int) collect($lines)->sum('max_amount');
        $discount = $this->multiplePetDiscount($serviceKey, count($lines), $amount, $maximum);
        $status = collect($lines)->contains(fn (array $line): bool => $line['status'] === 'estimate') ? 'estimate' : 'fixed';

        return [
            'status' => $status,
            'type' => $status,
            'amount' => $amount - $discount['amount'],
            'max_amount' => $maximum - $discount['max_amount'],
            'subtotal' => $amount,
            'max_subtotal' => $maximum,
            'discount' => $discount,
            'lines' => $lines,
            'nights' => $quantity,
            'currency' => config('waggies_pricing.currency', 'NGN'),
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function sizeRates(string $service, ?string $variant): array
    {
        return $this->services()[$service]['pricing']['pet_variants'][$variant]['size_rates']
            ?? $this->services()[$service]['variants'][$variant]['size_rates']
            ?? [];
    }

    private function sizeBasedQuote(string $service, ?string $variant, array $tierDefinition, array $pet, int $quantity): array
    {
        $weight = $pet['weight_kg'] ?? null;
        $size = $pet['size'] ?? $this->dogSizeForWeight($weight);
        $sizeRate = $this->sizeRates($service, $variant)[$size] ?? null;

        if ($sizeRate === null) {
            return [
                'status' => 'needs_input',
                'reason' => $service === 'boarding'
                    ? 'Add your dog\'s weight so we can estimate boarding cost.'
                    : 'Add your dog\'s weight so we can estimate grooming cost.',
            ];
        }

        $multiplier = max(1, $quantity);
        $adjustment = (int) ($tierDefinition['adjustment'] ?? 0);

        return [
            'status' => 'estimate',
            'type' => 'estimate',
            'amount' => ((int) $sizeRate['amount'] + $adjustment) * $multiplier,
            'max_amount' => ((int) $sizeRate['max_amount'] + $adjustment) * $multiplier,
            'currency' => config('waggies_pricing.currency', 'NGN'),
            'unit' => $this->services()[$service]['unit'] ?? null,
            'size' => $size,
            'weight_kg' => $weight,
        ];
    }

    private function boardingNights(string $service, array $details): int
    {
        if ($service !== 'boarding' || empty($details['check_in']) || empty($details['check_out'])) {
            return 1;
        }

        return max(1, Carbon::parse($details['check_in'])->diffInDays(Carbon::parse($details['check_out'])));
    }

    /**
     * @return array{amount: int, max_amount: int, percentage: int}
     */
    private function multiplePetDiscount(string $service, int $petCount, int $amount, int $maximum): array
    {
        $discount = config('waggies_pricing.discounts.multiple_pet', []);
        $percentage = (int) ($discount['percentage'] ?? 0);
        $appliesFrom = (int) ($discount['applies_from_pet'] ?? 2);

        if (($discount['enabled'] ?? false) !== true
            || ! in_array($service, $discount['applies_to'] ?? [], true)
            || $petCount < $appliesFrom
        ) {
            return ['amount' => 0, 'max_amount' => 0, 'percentage' => 0];
        }

        return [
            'amount' => (int) round($amount * $percentage / 100),
            'max_amount' => (int) round($maximum * $percentage / 100),
            'percentage' => $percentage,
        ];
    }

    private function dogSizeForWeight(mixed $weight): ?string
    {
        if (! is_numeric($weight) || (float) $weight < 0) {
            return null;
        }

        $weight = (float) $weight;

        if ($weight <= 10) {
            return 'small';
        }

        if ($weight <= 25) {
            return 'medium';
        }

        return 'large';
    }

    private function formatAmount(int $amount): string
    {
        return '₦'.number_format($amount);
    }
}
