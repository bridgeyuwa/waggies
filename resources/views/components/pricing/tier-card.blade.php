@php
    use App\Support\PricingQuote;

    $priceDisplay = match ($tier['type'] ?? 'fixed') {
        'estimate' => PricingQuote::formatNaira($tier['amount']).' – '.PricingQuote::formatNaira($tier['amount_max'] ?? $tier['amount']),
        'quote' => 'Quote',
        default => PricingQuote::formatNaira($tier['amount']),
    };

    $ctaLabel = match ($tier['type'] ?? 'fixed') {
        'quote' => 'Get Estimate',
        'estimate' => 'Get Estimate',
        default => 'Get Estimate',
    };
@endphp

<x-card.pricing
    :variant="($tier['featured'] ?? false) ? 'featured' : 'standard'"
    :tier-label="$tier['label']"
    :badge-label="$tier['badge'] ?? 'Most Popular'"
    :price="$priceDisplay"
    :unit="$unit"
    :features="$tier['features'] ?? []"
    :cta-label="$ctaLabel"
    :cta-href="$estimateUrl"
/>
