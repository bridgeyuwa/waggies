<?php

use App\Support\BookingPricingCatalog;
use Tests\TestCase;

uses(TestCase::class);

it('exposes only the active public services', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->serviceOptions(availableOnly: true))->toBe([
        'boarding' => 'Boarding',
        'vet-care' => 'Veterinary Care',
        'relocation' => 'Relocation',
    ])
        ->and($catalog->tiers('boarding', 'dogs'))->toBe([])
        ->and($catalog->serviceOptions())->not->toHaveKeys(['grooming', 'training', 'local-transport', 'boarding-exotic']);
});

it('prices dog boarding from the selected size per pet per night', function (): void {
    $quote = app(BookingPricingCatalog::class)->quote(
        'boarding',
        'dogs',
        null,
        ['species' => 'dog', 'size' => 'medium'],
        2,
    );

    expect($quote)->toMatchArray([
        'status' => 'estimate',
        'amount' => 24000,
        'max_amount' => 36000,
        'size' => 'medium',
        'unit' => 'per pet per night',
    ]);
});

it('does not derive dog size from weight', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->quote('boarding', 'dogs', null, ['species' => 'dog', 'weight_kg' => 22]))
        ->toMatchArray(['status' => 'needs_input'])
        ->and($catalog->quote('boarding', 'dogs', null, ['species' => 'dog', 'size' => 'small', 'weight_kg' => 40]))
        ->toMatchArray(['status' => 'estimate', 'size' => 'small']);
});

it('keeps dog size boundaries unambiguous and sends above 40kg to review', function (): void {
    $rates = config('waggies_pricing.services.boarding.variants.dogs.size_rates');

    expect($rates['small'])->toMatchArray([
        'max_weight_kg' => 10,
        'max_weight_inclusive' => true,
    ])
        ->and($rates['medium'])->toMatchArray([
            'min_weight_kg' => 10,
            'min_weight_inclusive' => false,
            'max_weight_kg' => 25,
            'max_weight_inclusive' => true,
        ])
        ->and($rates['large'])->toMatchArray([
            'min_weight_kg' => 25,
            'min_weight_inclusive' => false,
            'max_weight_kg' => 40,
            'max_weight_inclusive' => true,
        ])
        ->and($rates['manual-review'])->toMatchArray([
            'min_weight_kg' => 40,
            'min_weight_inclusive' => false,
            'manual_review' => true,
        ]);

    expect(app(BookingPricingCatalog::class)->quote(
        'boarding',
        'dogs',
        null,
        ['species' => 'dog', 'size' => 'manual-review'],
    ))->toMatchArray(['status' => 'quote', 'size' => 'manual-review']);
});

it('keeps cats on one request-only nightly boarding rate', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->requiresPetSize('boarding', 'cats'))->toBeFalse()
        ->and($catalog->variants('boarding')['cats'])->not->toHaveKey('tiers')
        ->and($catalog->quote('boarding', 'cats', null, ['species' => 'cat']))
        ->toMatchArray(['status' => 'quote', 'type' => 'quote']);
});

it('does not impose an artificial thirty-night maximum', function (): void {
    $quote = app(BookingPricingCatalog::class)->quote(
        'boarding',
        'dogs',
        null,
        ['species' => 'dog', 'size' => 'small'],
        31,
    );

    expect($quote)->toMatchArray([
        'status' => 'estimate',
        'amount' => 248000,
        'max_amount' => 372000,
    ]);
});

it('keeps multiple-pet discounts disabled and non-authoritative when enabled', function (): void {
    $service = [
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'details' => [
            'check_in' => '2026-10-01',
            'check_out' => '2026-10-04',
        ],
    ];

    $pets = [
        ['name' => 'Luna', 'species' => 'dog', 'size' => 'small'],
        ['name' => 'Milo', 'species' => 'dog', 'size' => 'small'],
    ];

    expect(config('waggies_pricing.discounts.multiple_pet.enabled'))->toBeFalse();

    config()->set('waggies_pricing.discounts.multiple_pet.enabled', true);
    config()->set('waggies_pricing.discounts.multiple_pet.percentage', 10);

    $quote = app(BookingPricingCatalog::class)->quoteForService($service, $pets);

    expect($quote)->toMatchArray([
        'amount' => 48000,
        'max_amount' => 72000,
        'discount_authority' => 'manual_quotation',
    ])
        ->and($quote['discount']['amount'])->toBe(2400);
});

it('keeps vaccination request-only and exposes standalone microchipping under veterinary care', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->quote('vet-care', 'vaccination-request', null, ['species' => 'dog']))
        ->toMatchArray(['status' => 'quote', 'type' => 'quote'])
        ->and($catalog->quote('vet-care', 'microchip', null, ['species' => 'cat']))
        ->toMatchArray(['status' => 'quote', 'type' => 'quote'])
        ->and(config('waggies_pricing.services.vet-care.variants.microchip.category'))->toBe('Identification');
});

it('keeps relocation limited to dog and cat import/export requests', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    expect($catalog->variantOptions('relocation', availableOnly: true))->toBe([
        'import' => 'Import',
        'export' => 'Export',
    ])
        ->and($catalog->isPetCompatible('relocation', 'import', 'dog'))->toBeTrue()
        ->and($catalog->isPetCompatible('relocation', 'export', 'cat'))->toBeTrue()
        ->and($catalog->isPetCompatible('relocation', 'import', 'exotic'))->toBeFalse()
        ->and($catalog->quote('relocation', 'import', null, ['species' => 'dog']))
        ->toMatchArray(['status' => 'quote', 'type' => 'quote']);
});

it('keeps every booking variant compatible with its declared pet types', function (): void {
    $catalog = app(BookingPricingCatalog::class);

    foreach ($catalog->services(availableOnly: true) as $serviceKey => $service) {
        foreach ($catalog->variants($serviceKey, availableOnly: true) as $variantKey => $variant) {
            $allowedPetTypes = array_values($variant['pet_types'] ?? $service['pet_types'] ?? []);

            expect($catalog->variantOptions($serviceKey, availableOnly: true))
                ->toHaveKey($variantKey)
                ->and($catalog->allowedPetTypes($serviceKey, $variantKey))
                ->toBe($allowedPetTypes);

            foreach (['dog', 'cat', 'exotic'] as $petType) {
                expect($catalog->isPetCompatible($serviceKey, $variantKey, $petType))
                    ->toBe(in_array($petType, $allowedPetTypes, true));
            }
        }
    }
});
