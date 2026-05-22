<?php

namespace App\Support;

use Illuminate\Support\Arr;

class PricingQuote
{
    /**
     * @return array<string, mixed>|null
     */
    public static function service(string $key): ?array
    {
        return config("waggies.pricing.services.{$key}");
    }

    /**
     * @return array<string, string>
     */
    public static function serviceOptions(): array
    {
        return collect(config('waggies.pricing.services', []))
            ->mapWithKeys(fn (array $service, string $key): array => [$key => $service['label']])
            ->all();
    }

    public static function formatNaira(int $amount): string
    {
        return '₦'.number_format($amount);
    }

    /**
     * @param  array<string, string|int|null>  $context
     */
    public static function contactUrl(string $intent, array $context = []): string
    {
        return route('contact', array_filter([
            'intent' => $intent,
            'service' => $context['service'] ?? null,
            'variant' => $context['variant'] ?? null,
            'tier' => $context['tier'] ?? null,
            'quantity' => $context['quantity'] ?? null,
            'summary' => $context['summary'] ?? null,
        ], fn ($value) => $value !== null && $value !== ''));
    }

    public static function estimateUrl(?string $service = null, ?string $variant = null, ?string $tier = null): string
    {
        return route('services.pricing', array_filter([
            'service' => $service,
            'variant' => $variant,
            'tier' => $tier,
        ], fn ($value) => $value !== null && $value !== ''));
    }

    /**
     * @return array{service: string, variant: ?string}
     */
    public static function resolveServiceContext(?string $service, ?string $variant): array
    {
        $aliases = config('waggies.pricing.aliases', []);

        if ($service && isset($aliases[$service])) {
            return [
                'service' => $aliases[$service]['service'],
                'variant' => $aliases[$service]['variant'] ?? $variant,
            ];
        }

        return [
            'service' => $service ?? '',
            'variant' => $variant,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function calculatorPayload(?string $service = null, ?string $variant = null, ?string $tier = null): array
    {
        $resolved = self::resolveServiceContext($service, $variant);

        return [
            'services' => config('waggies.pricing.services', []),
            'selectedService' => $resolved['service'],
            'selectedVariant' => $resolved['variant'],
            'selectedTier' => $tier,
            'contactUrls' => [
                'book' => 'book',
                'save' => 'save',
                'consult' => 'consult',
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $input
     */
    public static function buildPrefillMessage(array $input): string
    {
        $serviceKey = (string) Arr::get($input, 'service', '');
        $service = self::service($serviceKey);
        $variantKey = Arr::get($input, 'variant');
        $tierKey = (string) Arr::get($input, 'tier', '');
        $intent = (string) Arr::get($input, 'intent', 'consult');
        $summary = (string) Arr::get($input, 'summary', '');

        $lines = [];

        if ($service) {
            $label = $service['label'];
            if ($variantKey && isset($service['variants'][$variantKey]['label'])) {
                $label .= ' — '.$service['variants'][$variantKey]['label'];
            }
            $lines[] = "Service: {$label}";
        }

        if ($tierKey !== '' && $service) {
            $tier = self::findTier($service, $variantKey, $tierKey);
            if ($tier) {
                $lines[] = 'Package: '.$tier['label'];
            }
        }

        if ($summary !== '') {
            $lines[] = 'Estimate: '.$summary;
        }

        $intentLabel = match ($intent) {
            'book' => 'I would like to book this service.',
            'save' => 'Please save this estimate and follow up with next steps.',
            default => 'I would like to speak with a specialist about this.',
        };

        $lines[] = $intentLabel;

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $service
     * @return array<string, mixed>|null
     */
    public static function findTier(array $service, ?string $variantKey, string $tierKey): ?array
    {
        if ($variantKey && isset($service['variants'][$variantKey]['tiers'][$tierKey])) {
            return $service['variants'][$variantKey]['tiers'][$tierKey];
        }

        if (isset($service['tiers'][$tierKey])) {
            return $service['tiers'][$tierKey];
        }

        return null;
    }
}
