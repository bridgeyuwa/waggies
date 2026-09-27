<?php

use App\Support\BookingPricingCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the public catalogue contains only the three active services', function () {
    expect(app(BookingPricingCatalog::class)->serviceOptions())
        ->toBe([
            'boarding' => 'Boarding',
            'vet-care' => 'Veterinary Care',
            'relocation' => 'Relocation',
        ]);
});

test('boarding has no package or tier catalogue', function () {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->tiers('boarding', 'dogs'))
        ->toBe([])
        ->and($catalog->tierOptions('boarding', 'dogs'))
        ->toBe([])
        ->and($catalog->requiresPetWeight('boarding', 'dogs'))
        ->toBeFalse();
});

test('manual quotation remains authoritative over indicative boarding calculations', function () {
    $catalog = app(BookingPricingCatalog::class);
    $quote = $catalog->quoteForService([
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => [
            'check_in' => now()->addDays(4)->toDateString(),
            'check_out' => now()->addDays(7)->toDateString(),
        ],
    ], [
        ['name' => 'Milo', 'species' => 'dog', 'size' => 'small'],
        ['name' => 'Luna', 'species' => 'dog', 'size' => 'medium'],
    ]);

    expect($quote['draft'])
        ->toBeTrue()
        ->and($quote['authority'])->toBe('staff_quotation')
        ->and($quote['discount_authority'])->toBe('manual_quotation')
        ->and($quote['amount'])->toBe(60000)
        ->and($quote['discount']['amount'])->toBe(0);
});

test('multiple pet discounts remain configurable but disabled and boarding-only', function () {
    $discount = config('waggies_pricing.discounts.multiple_pet');

    expect($discount['enabled'])->toBeFalse()
        ->and($discount['applies_to'])->toBe(['boarding'])
        ->and($discount['calculation'])->toBe('manual_during_quotation');
});

test('vaccine and microchip options are request-only while relocation is quote-only', function () {
    $services = config('waggies_pricing.services');

    expect($services['vet-care']['variants']['vaccination-request']['request_only'])->toBeTrue()
        ->and($services['vet-care']['variants']['vaccination-request'])->not->toHaveKey('amount')
        ->and($services['vet-care']['variants']['microchip']['category'])->toBe('Identification')
        ->and($services['vet-care']['variants']['microchip']['request_only'])->toBeTrue()
        ->and($services['relocation']['pricing_mode'])->toBe('manual_quote')
        ->and($services['relocation']['variants'])->toHaveKeys(['import', 'export']);
});
