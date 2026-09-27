<?php

use App\Support\BookingPricingCatalog;
use Tests\TestCase;

uses(TestCase::class);

it('exposes only the three active public services', function (): void {
    expect(app(BookingPricingCatalog::class)->serviceOptions())->toBe([
        'boarding' => 'Boarding',
        'vet-care' => 'Veterinary Care',
        'relocation' => 'Relocation',
    ]);
});

it('does not expose packages or tiers in the active catalogue', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->tierOptions('boarding', 'dogs'))->toBe([])
        ->and($catalog->tierOptions('vet-care'))->toBe([])
        ->and($catalog->tiers('boarding', 'dogs'))->toBe([]);
});

it('prices dog boarding directly by selected size and overnight stays', function (): void {
    $quote = app(BookingPricingCatalog::class)->quote('boarding', 'dogs', null, ['species' => 'dog', 'size' => 'medium'], 3);

    expect($quote)->toMatchArray([
        'status' => 'estimate',
        'amount' => 36000,
        'max_amount' => 54000,
        'size' => 'medium',
    ])->and($quote)->not->toHaveKey('weight_kg');
});

it('does not derive dog size from weight', function (): void {
    expect(app(BookingPricingCatalog::class)->quote('boarding', 'dogs', null, ['species' => 'dog', 'weight_kg' => 22]))
        ->toMatchArray(['status' => 'needs_input']);
});

it('publishes unambiguous operational size guidance', function (): void {
    $rates = config('waggies_pricing.services.boarding.variants.dogs.size_rates');

    expect($rates['small'])->toMatchArray([
        'max_weight_kg' => 10,
        'max_weight_inclusive' => true,
    ])->and($rates['medium'])->toMatchArray([
        'min_weight_kg' => 10,
        'min_weight_inclusive' => false,
        'max_weight_kg' => 25,
        'max_weight_inclusive' => true,
    ])->and($rates['large'])->toMatchArray([
        'min_weight_kg' => 25,
        'min_weight_inclusive' => false,
        'max_weight_kg' => 40,
        'max_weight_inclusive' => true,
    ])->and($rates['manual-review'])->toMatchArray([
        'min_weight_kg' => 40,
        'min_weight_inclusive' => false,
        'manual_review' => true,
    ]);
});

it('sends above forty kilogram or unusual dogs to manual review', function (): void {
    expect(app(BookingPricingCatalog::class)->quote('boarding', 'dogs', null, ['species' => 'dog', 'size' => 'manual-review']))
        ->toMatchArray(['status' => 'quote', 'size' => 'manual-review']);
});

it('uses one staff-confirmed nightly path for cats', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->requiresPetSize('boarding', 'cats'))->toBeFalse()
        ->and($catalog->quote('boarding', 'cats', null, ['species' => 'cat']))
        ->toMatchArray(['status' => 'quote']);
});

it('does not enforce an artificial thirty-night maximum', function (): void {
    $quote = app(BookingPricingCatalog::class)->quoteForService([
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => [
            'check_in' => '2026-10-01',
            'check_out' => '2026-11-01',
        ],
    ], [['name' => 'Bruno', 'species' => 'dog', 'size' => 'small']]);

    expect($quote)->toMatchArray([
        'nights' => 31,
        'amount' => 248000,
        'max_amount' => 372000,
    ]);
});

it('keeps multiple-pet discount configurable but disabled by default', function (): void {
    $service = [
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => ['check_in' => '2026-10-01', 'check_out' => '2026-10-04'],
    ];
    $pets = [
        ['name' => 'Bruno', 'species' => 'dog', 'size' => 'small'],
        ['name' => 'Milo', 'species' => 'dog', 'size' => 'small'],
    ];

    $disabled = app(BookingPricingCatalog::class)->quoteForService($service, $pets);

    expect($disabled)->toMatchArray([
        'amount' => 48000,
        'max_amount' => 72000,
        'authority' => 'staff_quotation',
        'draft' => true,
    ])->and($disabled['discount']['percentage'])->toBe(0);

    config()->set('waggies_pricing.discounts.multiple_pet.enabled', true);
    config()->set('waggies_pricing.discounts.multiple_pet.percentage', 10);
    $enabled = app(BookingPricingCatalog::class)->quoteForService($service, $pets);

    expect($enabled['amount'])->toBe(45600)
        ->and($enabled['discount']['percentage'])->toBe(10);
});

it('does not apply the boarding discount to veterinary care', function (): void {
    config()->set('waggies_pricing.discounts.multiple_pet.enabled', true);

    $quote = app(BookingPricingCatalog::class)->quoteForService([
        'service_key' => 'vet-care',
        'service_variant' => 'wellness-consultation',
    ], [
        ['name' => 'Bruno', 'species' => 'dog'],
        ['name' => 'Milo', 'species' => 'dog'],
    ]);

    expect($quote['discount']['percentage'])->toBe(0);
});

it('keeps vaccination and microchipping request-only until products are curated', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->quote('vet-care', 'vaccination-request', null, ['species' => 'dog']))
        ->toMatchArray(['status' => 'quote'])
        ->and($catalog->quote('vet-care', 'microchip', null, ['species' => 'cat']))
        ->toMatchArray(['status' => 'quote']);
});

it('limits relocation to dog and cat import or export requests', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->variantOptions('relocation'))->toBe(['import' => 'Import', 'export' => 'Export'])
        ->and($catalog->isPetCompatible('relocation', 'import', 'dog'))->toBeTrue()
        ->and($catalog->isPetCompatible('relocation', 'export', 'cat'))->toBeTrue()
        ->and($catalog->isPetCompatible('relocation', 'import', 'bird'))->toBeFalse()
        ->and($catalog->serviceOptions())->not->toHaveKey('local-transport');
});
