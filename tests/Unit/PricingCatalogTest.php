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

it('calculates size-based dog grooming without requiring an exact weight', function (): void {
    expect(app(BookingPricingCatalog::class)->quote('grooming', 'dogs', 'full', ['size' => 'medium']))
        ->toMatchArray([
            'status' => 'estimate',
            'amount' => 16000,
            'max_amount' => 23000,
            'size' => 'medium',
            'weight_kg' => null,
        ]);
});

it('provides configurable dog size labels and breed examples', function (): void {
    expect(app(BookingPricingCatalog::class)->sizeOptions('grooming', 'dogs')['small'])
        ->toMatchArray([
            'label' => 'Small',
            'examples' => 'Chihuahua, Yorkshire Terrier, Toy Poodle',
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

it('rejects pets that do not match the selected service variant', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->isPetCompatible('boarding', 'dogs', 'dog'))->toBeTrue()
        ->and($catalog->isPetCompatible('boarding', 'dogs', 'cat'))->toBeFalse()
        ->and($catalog->petCompatibilityReason('boarding', 'dogs', 'cat'))
        ->toBe('Only dogs can be assigned to this service.');
});

it('uses service-level compatibility rules for dog training', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->isPetCompatible('training', null, 'dog'))->toBeTrue()
        ->and($catalog->isPetCompatible('training', null, 'cat'))->toBeFalse();
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

    config()->set('waggies_pricing.discounts.multiple_pet.percentage', 10);

    $quote = app(BookingPricingCatalog::class)->quoteForService($service, [
        ['name' => 'Bruno', 'weight_kg' => 8],
        ['name' => 'Milo', 'weight_kg' => 8],
    ]);

    expect($quote)
        ->toMatchArray([
            'status' => 'estimate',
            'amount' => 45600,
            'max_amount' => 68400,
            'nights' => 3,
        ])
        ->and($quote['discount']['percentage'])->toBe(10);
});

it('honours the configured zero percent multiple-pet discount', function (): void {
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
            'amount' => 48000,
            'max_amount' => 72000,
            'subtotal' => 48000,
            'max_subtotal' => 72000,
        ])
        ->and($quote['discount']['percentage'])->toBe(0);
});
