<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

dataset('valid booking service and pet permutations', [
    'boarding dog' => [
        ['service' => 'boarding', 'variant' => 'dogs'],
        ['name' => 'Bruno', 'species' => 'dog', 'size' => 'medium', 'age' => null, 'sex' => 'male', 'breed' => null],
        [
            'requested_date' => null,
            'details' => [
                'check_in' => '2026-10-10',
                'check_out' => '2026-10-12',
                'emergency_vet_authorization' => 'authorized',
            ],
        ],
    ],
    'boarding cat' => [
        ['service' => 'boarding', 'variant' => 'cats'],
        ['name' => 'Luna', 'species' => 'cat', 'size' => null, 'age' => null, 'sex' => 'female', 'breed' => null],
        [
            'requested_date' => null,
            'details' => [
                'check_in' => '2026-10-10',
                'check_out' => '2026-10-12',
                'emergency_vet_authorization' => 'discuss',
            ],
        ],
    ],
    'veterinary dog with multiple needs' => [
        ['service' => 'vet-care', 'variant' => 'wellness-consultation'],
        ['name' => 'Bruno', 'species' => 'dog', 'size' => null, 'age' => '4-7-years', 'sex' => 'male', 'breed' => null],
        [
            'requested_date' => '2026-10-10',
            'details' => [
                'care_needs' => ['wellness-consultation', 'vaccination-request'],
                'reason' => 'Routine wellness and vaccination review.',
                'urgency' => 'routine',
            ],
        ],
    ],
    'veterinary cat' => [
        ['service' => 'vet-care', 'variant' => 'vaccination-request'],
        ['name' => 'Luna', 'species' => 'cat', 'size' => null, 'age' => 'not-sure', 'sex' => 'female', 'breed' => null],
        [
            'requested_date' => '2026-10-10',
            'details' => [
                'care_needs' => ['vaccination-request'],
                'reason' => 'Routine vaccination review.',
                'urgency' => 'soon',
            ],
        ],
    ],
    'relocation import dog' => [
        ['service' => 'relocation', 'variant' => 'import'],
        ['name' => 'Bruno', 'species' => 'dog', 'size' => null, 'age' => '1-3-years', 'sex' => 'male', 'breed' => 'Mixed breed'],
        [
            'requested_date' => '2026-10-10',
            'details' => [
                'travel_timing' => 'exact',
                'origin_country' => 'GH',
                'destination_country' => 'NG',
                'flight_status' => 'not_booked',
                'microchip_status' => 'yes',
                'documentation_status' => 'ready',
            ],
        ],
    ],
    'relocation export cat' => [
        ['service' => 'relocation', 'variant' => 'export'],
        ['name' => 'Luna', 'species' => 'cat', 'size' => null, 'age' => '8-10-years', 'sex' => 'female', 'breed' => 'Domestic shorthair'],
        [
            'requested_date' => '2026-10-10',
            'details' => [
                'travel_timing' => 'exact',
                'origin_country' => 'NG',
                'destination_country' => 'GH',
                'flight_status' => 'need_help',
                'microchip_status' => 'unknown',
                'documentation_status' => 'in-progress',
            ],
        ],
    ],
]);

it('completes every active service and compatible pet permutation', function (array $context, array $pet, array $serviceData): void {
    $serviceData['requested_date'] = $serviceData['requested_date'] === null
        ? null
        : now()->addDays(7)->toDateString();

    if (array_key_exists('check_in', $serviceData['details'])) {
        $serviceData['details']['check_in'] = now()->addDays(7)->toDateString();
        $serviceData['details']['check_out'] = now()->addDays(9)->toDateString();
    }

    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => $context,
    ]);

    if (isset($serviceData['details']['care_needs'])) {
        $component->set('services.0.details.care_needs', $serviceData['details']['care_needs']);
    }

    $component
        ->call('nextStep')
        ->assertSet('step', 2);

    foreach ($pet as $key => $value) {
        if ($value !== null) {
            $component->set("pets.0.{$key}", $value);
        }
    }

    $component
        ->call('nextStep')
        ->assertSet('step', 3)
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.requested_date', $serviceData['requested_date'])
        ->set('services.0.details', $serviceData['details'])
        ->call('nextStep')
        ->assertSet('step', 4)
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'permutation@example.com')
        ->set('contact.phone_country', 'NG')
        ->set('contact.phone_number', '08080811902')
        ->call('nextStep')
        ->assertSet('step', 5)
        ->assertHasNoErrors();
})->with('valid booking service and pet permutations');

it('does not carry stale assignments across service-variant changes', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'export'],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->call('variantChanged', 0, 'import')
        ->assertSet('services.0.assigned_pet_ids', [])
        ->assertSet('services.0.details.origin_country', null)
        ->assertSet('services.0.details.destination_country', 'NG');
});

it('keeps a single compatible pet checkbox assignment as an array', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->set('pets.0.species', 'dog')
        ->set('services.0.assigned_pet_ids', true)
        ->assertSet('services.0.assigned_pet_ids', [0]);
});

dataset('single pet checkbox service modes', [
    'boarding dogs' => [['service' => 'boarding', 'variant' => 'dogs'], 'dog'],
    'boarding cats' => [['service' => 'boarding', 'variant' => 'cats'], 'cat'],
    'veterinary care for dogs' => [['service' => 'vet-care', 'variant' => 'wellness-consultation'], 'dog'],
    'veterinary care for cats' => [['service' => 'vet-care', 'variant' => 'wellness-consultation'], 'cat'],
    'relocation import' => [['service' => 'relocation', 'variant' => 'import'], 'dog'],
    'relocation export' => [['service' => 'relocation', 'variant' => 'export'], 'cat'],
]);

it('keeps a single compatible pet checkbox assignment as an array across service modes', function (array $context, string $petType): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => $context,
    ])
        ->set('pets.0.species', $petType)
        ->set('services.0.assigned_pet_ids', true)
        ->assertSet('services.0.assigned_pet_ids', [0]);
})->with('single pet checkbox service modes');

it('clears a single compatible pet checkbox assignment when unchecked', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->set('pets.0.species', 'dog')
        ->set('services.0.assigned_pet_ids', true)
        ->set('services.0.assigned_pet_ids', false)
        ->assertSet('services.0.assigned_pet_ids', []);
});

it('keeps a single active veterinary care checkbox as an array', function (): void {
    $variants = config('waggies_pricing.services.vet-care.variants');

    config([
        'waggies_pricing.services.vet-care.variants' => [
            'wellness-consultation' => $variants['wellness-consultation'],
        ],
    ]);

    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'vet-care'],
    ])
        ->set('services.0.details.care_needs', true)
        ->assertSet('services.0.details.care_needs', ['wellness-consultation']);
});

it('blocks invalid relocation directions and unassigned pets before continuing', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'export'],
    ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->set('services.0.details', [
            'travel_timing' => 'exact',
            'origin_country' => 'NG',
            'destination_country' => 'NG',
            'flight_status' => 'not_booked',
            'microchip_status' => 'yes',
            'documentation_status' => 'ready',
        ])
        ->set('services.0.requested_date', now()->addDays(7)->toDateString())
        ->set('step', 3)
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertHasErrors([
            'services.0.assigned_pet_ids',
            'services.0.details.destination_country',
        ]);
});

it('moves through the booking steps in order after each step is valid', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->call('nextStep')
        ->assertSet('step', 4)
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'ada@example.com')
        ->set('contact.phone_country', 'NG')
        ->set('contact.phone_number', '08080811902')
        ->call('nextStep');

    $component->assertSet('step', 5);
});

it('preselects the only pet when entering the matching step', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertSet('services.0.assigned_pet_ids', [0]);
});

it('preselects the only pet for every compatible selected service', function (): void {
    Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('toggleService', 'relocation')
        ->call('variantChanged', 1, 'import')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertSet('services.0.assigned_pet_ids', [0])
        ->assertSet('services.1.assigned_pet_ids', [0]);
});

it('leaves the only pet unassigned for an incompatible service', function (): void {
    config([
        'waggies_pricing.services.boarding.pet_types' => ['dog'],
        'waggies_pricing.services.relocation.pet_types' => ['cat'],
        'waggies_pricing.services.relocation.variants.import.pet_types' => ['cat'],
    ]);

    Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('toggleService', 'relocation')
        ->call('variantChanged', 1, 'import')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertSet('services.0.assigned_pet_ids', [0])
        ->assertSet('services.1.assigned_pet_ids', []);
});

it('preserves the automatic assignment when another pet is added', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->call('addPet')
        ->assertSet('pets.1.name', null)
        ->assertSet('services.0.assigned_pet_ids', [0]);
});

it('requires a new match when the only pet clears an automatic assignment', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('services.0.assigned_pet_ids', false)
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertHasErrors(['services.0.assigned_pet_ids' => 'required']);
});

it('renders friendly labels for every missing relocation detail', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'relocation')
        ->set('services.0.service_variant', 'export')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('services.0.assigned_pet_ids', [0])
        ->call('nextStep');

    $component
        ->assertSet('step', 3)
        ->assertSee('The travel timing field is required.')
        ->assertSee('The destination country field is required.')
        ->assertSee('The flight booking status field is required.')
        ->assertSee('The existing microchip status field is required.')
        ->assertSee('The relocation document readiness field is required.')
        ->assertDontSee('The services.0.details.microchip status field is required.');
});

it('shows missing names for every added pet when submission is attempted', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->call('addPet')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->set('pets.1.species', 'dog')
        ->set('pets.1.size', 'medium')
        ->set('pets.1.sex', 'female')
        ->set('services.0.assigned_pet_ids', [0, 1])
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'ada@example.com')
        ->set('contact.phone_country', 'NG')
        ->set('contact.phone_number', '08080811902')
        ->set('step', 5)
        ->call('submit')
        ->assertHasErrors([
            'pets.0.name' => 'required',
            'pets.1.name' => 'required',
        ])
        ->assertSee('The pet name field is required for 2 pets.');
});

it('groups repeated pet field errors in the summary', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->call('nextStep')
        ->call('addPet')
        ->call('nextStep');

    preg_match('/<div id="booking-error-summary".*?<\/div>/s', $component->html(), $matches);

    expect($matches[0] ?? '')
        ->toContain('for 2 pets')
        ->and(substr_count($matches[0] ?? '', 'The pet name field is required for 2 pets.'))->toBe(1)
        ->and(substr_count($matches[0] ?? '', 'The pet type field is required for 2 pets.'))->toBe(1)
        ->and(substr_count($matches[0] ?? '', 'The sex field is required for 2 pets.'))->toBe(1);
});

it('groups repeated service field errors in the summary', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('toggleService', 'relocation')
        ->set('services.1.service_variant', 'export')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('services.0.assigned_pet_ids', false)
        ->set('services.1.assigned_pet_ids', false)
        ->call('nextStep');

    preg_match('/<div id="booking-error-summary".*?<\/div>/s', $component->html(), $matches);

    expect($matches[0] ?? '')
        ->toContain('The assigned pet field is required for 2 services.')
        ->and(substr_count($matches[0] ?? '', 'The assigned pet field is required for 2 services.'))->toBe(1);
});

it('includes pet-name errors when submission also has an earlier service error', function (): void {
    Livewire::test('booking-request-wizard')
        ->call('addPet')
        ->set('step', 5)
        ->call('submit')
        ->assertHasErrors([
            'services.0.service_key' => 'required',
            'pets.0.name' => 'required',
            'pets.1.name' => 'required',
        ])
        ->assertSee('The service field is required.')
        ->assertSee('The pet name field is required for 2 pets.')
        ->assertSee('(Step 2)')
        ->assertDontSee('href="#booking-pet-0-name"', false);
});

it('stops on the pet step when an age-dependent service has no life stage', function (): void {
    Livewire::test('booking-request-wizard')
        ->call('toggleService', 'vet-care')
        ->set('services.0.details.care_needs', ['wellness-consultation'])
        ->call('nextStep')
        ->assertSet('step', 2)
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->assertSet('step', 2)
        ->assertHasErrors(['pets.0.age' => 'required']);
});

it('continues from the pet step after the user chooses an explicit life stage', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'vet-care')
        ->set('services.0.details.care_needs', ['wellness-consultation'])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('pets.0.age', 'not-sure')
        ->call('nextStep');

    $component->assertSet('step', 3)->assertHasNoErrors();
});

it('clears conditional date errors when the travel timing makes those dates optional', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details', [
            'travel_timing' => 'window',
            'origin_country' => 'GH',
            'destination_country' => 'NG',
            'flight_status' => 'not_booked',
            'microchip_status' => 'yes',
            'documentation_status' => 'ready',
        ])
        ->call('nextStep')
        ->assertHasErrors([
            'services.0.requested_date' => 'required',
            'services.0.requested_end_date' => 'required',
        ])
        ->set('services.0.details.travel_timing', 'not_decided');

    $component->assertHasNoErrors();
});

it('does not show the global validation summary for a field-level error before navigation is attempted', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.name', null)
        ->assertHasErrors(['pets.0.name']);

    expect($component->html())->not->toContain('id="booking-error-summary"');
});

it('validates only the text field that loses focus', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->call('fieldBlurred', 'pets.0.name');

    expect($component->errors()->has('pets.0.name'))->toBeTrue()
        ->and($component->errors()->has('pets.0.age'))->toBeFalse()
        ->and($component->get('touchedFields'))->toBe(['pets.0.name']);
});

it('clears the other-country-code error when the phone country changes back', function (): void {
    $component = Livewire::test('booking-request-wizard')
        ->set('contact.phone_country', 'OTHER')
        ->set('contact.phone_other_country_code', '+1')
        ->set('contact.phone_other_country_code', null)
        ->assertHasErrors(['contact.phone_other_country_code'])
        ->set('contact.phone_country', 'NG');

    expect($component->errors()->has('contact.phone_other_country_code'))->toBeFalse();
});

it('revalidates a touched check-out date when the check-in date changes', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.check_in', now()->addDays(10)->toDateString());

    $component->assertHasErrors(['services.0.details.check_out']);
});

it('clears a conditional breed error when a pet is unassigned from relocation', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->call('nextStep')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.breed', null)
        ->assertHasErrors(['pets.0.breed'])
        ->set('services.0.assigned_pet_ids', false);

    expect($component->errors()->has('pets.0.breed'))->toBeFalse();
});
