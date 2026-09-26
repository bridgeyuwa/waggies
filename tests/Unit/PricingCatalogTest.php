<?php

use App\Support\BookingPricingCatalog;
use Tests\TestCase;

uses(TestCase::class);

it('calculates dog grooming estimates from weight-derived size and package adjustment', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->quote('grooming', 'dogs', 'full', ['weight_kg' => 22]))
        ->toMatchArray([
            'status' => 'estimate',
            'amount' => 16000,
            'max_amount' => 23000,
            'size' => 'medium',
            'weight_kg' => 22,
        ]);
});

it('shows size-aware ranges beside dog grooming package choices', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->priceLabel('grooming', 'dogs', $catalog->tiers('grooming', 'dogs', true)['full']))
        ->toBe('From ₦13,000–₦33,000');
});

it('keeps cat grooming tier prices flat and does not require size', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->quote('grooming', 'cats', 'full'))
        ->toMatchArray([
            'status' => 'fixed',
            'amount' => 18000,
            'max_amount' => 18000,
        ]);
});

it('returns a required input state when a dog grooming weight is missing', function (): void {
    expect(app(BookingPricingCatalog::class)->quote('grooming', 'dogs', 'bath'))
        ->toMatchArray([
            'status' => 'needs_input',
        ]);
});

it('does not price a suspended grooming tier', function (): void {
    config()->set('waggies_pricing.services.grooming.pricing.pet_variants.dogs.tiers.full.enabled', false);

    expect(app(BookingPricingCatalog::class)->quote('grooming', 'dogs', 'full', ['weight_kg' => 22]))
        ->toMatchArray([
            'status' => 'unavailable',
        ]);
});

it('keeps a globally suspended grooming tier disabled for every pet variant', function (): void {
    config()->set('waggies_pricing.services.grooming.tiers.full.enabled', false);

    expect(app(BookingPricingCatalog::class)->quote('grooming', 'cats', 'full'))
        ->toMatchArray([
            'status' => 'unavailable',
        ]);
});

it('calculates dog boarding from weight, tier, and nights', function (): void {
    expect(app(BookingPricingCatalog::class)->quote('boarding', 'dogs', 'premium', ['weight_kg' => 22], 3))
        ->toMatchArray([
            'status' => 'estimate',
            'amount' => 54000,
            'max_amount' => 72000,
            'size' => 'medium',
        ]);
});

it('applies the configurable boarding discount only to additional pets in one service item', function (): void {
    $service = [
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'pricing_tier' => 'basic',
        'details' => [
            'check_in' => '2026-10-01',
            'check_out' => '2026-10-04',
        ],
    ];

    $quote = app(BookingPricingCatalog::class)->quoteForService($service, [
        ['name' => 'Bruno', 'weight_kg' => 8],
        ['name' => 'Milo', 'weight_kg' => 8],
    ]);

    expect($quote)
        ->toMatchArray([
            'status' => 'estimate',
            'amount' => 43200,
            'max_amount' => 64800,
            'nights' => 3,
        ])
        ->and($quote['discount']['percentage'])->toBe(10);
});
