<?php

use App\Actions\CreateBookingRequest;
use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\Pages\EditBookingRequest;
use App\Filament\Resources\BookingRequests\RelationManagers\PetsRelationManager;
use App\Filament\Resources\BookingRequests\RelationManagers\ServicesRelationManager;
use App\Models\BookingRequest;
use App\Models\BookingRequestPet;
use App\Models\BookingRequestService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function bookingRequestPayload(array $overrides = []): array
{
    return array_merge([
        'name' => 'Ada Obi',
        'email' => 'ada@example.com',
        'phone' => '0808 081 1902',
        'service_key' => 'boarding',
        'service_variant' => 'dogs',
        'requested_date' => now()->addDays(7)->toDateString(),
        'requested_time' => '10:30',
        'pet_name' => 'Bruno',
        'pet_type' => 'dog',
        'location' => 'Maitama, Abuja',
        'message' => 'Bruno needs a calm boarding routine.',
        'website' => '',
    ], $overrides);
}

it('renders only active services and no package or tier controls', function (): void {
    $this->get(route('book'))
        ->assertOk()
        ->assertSee('Send Waggies a booking request')
        ->assertSee('Boarding')
        ->assertSee('Veterinary Care')
        ->assertSee('Relocation')
        ->assertDontSee('Grooming')
        ->assertDontSee('Dog Training')
        ->assertDontSee('Local Transport')
        ->assertDontSee('Exotic')
        ->assertDontSee('pricing_tier')
        ->assertSee('Nothing is charged yet');
});

it('preserves active boarding context while moving pet type selection into pet assignment', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'cats'],
    ])
        ->assertSet('services.0.service_key', 'boarding')
        ->assertSet('services.0.service_variant', null)
        ->set('step', 2)
        ->assertSee('value="dog"', false)
        ->assertSee('value="cat"', false);
});

it('offers both dog and cat choices for boarding and keeps the union across services', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('step', 2)
        ->assertSee('value="dog"', false)
        ->assertSee('value="cat"', false)
        ->call('addService')
        ->call('chooseAdditionalService', 'relocation')
        ->call('variantChanged', 1, 'import')
        ->set('step', 2)
        ->assertSee('value="dog"', false)
        ->assertSee('value="cat"', false);
});

it('requires at least one veterinary care need before leaving the services step', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'vet-care'],
    ])
        ->call('nextStep')
        ->assertSet('step', 1)
        ->assertHasErrors(['services.0.details.care_needs' => 'required'])
        ->set('services.0.details.care_needs', ['wellness-consultation', 'vaccination-request'])
        ->call('nextStep')
        ->assertSet('step', 2)
        ->assertHasNoErrors();
});

it('initializes veterinary care needs as an array for checkbox binding', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'vet-care'],
    ])
        ->assertSet('services.0.details.care_needs', [])
        ->set('services.0.details.care_needs', ['wellness-consultation'])
        ->assertSet('services.0.details.care_needs', ['wellness-consultation'])
        ->assertSee('Wellness consultation');
});

it('preserves an incompatible pet type and blocks stage two with a clear validation error', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('pets.0.name', 'Luna')
        ->set('pets.0.species', 'other')
        ->set('pets.0.sex', 'female')
        ->set('step', 2)
        ->call('nextStep')
        ->assertSet('step', 2)
        ->assertSet('pets.0.species', 'other')
        ->assertHasErrors(['pets.0.species' => 'in'])
        ->assertSee('This pet type is not compatible with the selected services.');
});

it('keeps booking select placeholders as empty values with field-specific labels', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('step', 2)
        ->assertSee('<option value="">Choose a type</option>', false)
        ->assertSee('<option value="">Choose age or life stage</option>', false)
        ->assertSee('<option value="">Choose sex</option>', false)
        ->set('step', 3)
        ->assertSee('<option value="">Choose an option</option>', false);
});

it('renders the configured pet age and life-stage options', function (): void {
    Livewire::test('booking-request-wizard')
        ->set('step', 2)
        ->assertSee('Under 6 months')
        ->assertSee('6–12 months')
        ->assertSee('1–3 years')
        ->assertSee('4–7 years')
        ->assertSee('8–10 years')
        ->assertSee('11+ years')
        ->assertSee('Not sure');
});

dataset('age-required booking variants', [
    'wellness consultation' => [
        'vet-care',
        'wellness-consultation',
        [
            'requested_date' => '2026-10-05',
            'reason' => 'Routine wellness check.',
            'urgency' => 'routine',
        ],
    ],
    'comprehensive examination' => [
        'vet-care',
        'comprehensive-examination',
        [
            'requested_date' => '2026-10-05',
            'reason' => 'A full examination.',
            'urgency' => 'routine',
        ],
    ],
    'vaccination request' => [
        'vet-care',
        'vaccination-request',
        [
            'requested_date' => '2026-10-05',
            'reason' => 'Vaccination request.',
            'urgency' => 'routine',
        ],
    ],
    'microchip' => [
        'vet-care',
        'microchip',
        [
            'requested_date' => '2026-10-05',
            'reason' => 'Microchip implantation.',
            'urgency' => 'routine',
        ],
    ],
    'relocation import' => [
        'relocation',
        'import',
        [
            'requested_date' => '2026-10-05',
            'origin_country' => 'GH',
            'destination_country' => 'NG',
            'microchip_status' => 'yes',
            'documentation_status' => 'ready',
        ],
    ],
    'relocation export' => [
        'relocation',
        'export',
        [
            'requested_date' => '2026-10-05',
            'origin_country' => 'NG',
            'destination_country' => 'GH',
            'microchip_status' => 'yes',
            'documentation_status' => 'ready',
        ],
    ],
]);

it('requires a valid age or life stage for every veterinary and relocation variant', function (string $service, string $variant, array $details): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => $service, 'variant' => $variant],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.requested_date', $details['requested_date'])
        ->set('services.0.details', array_diff_key($details, ['requested_date' => true]))
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.sex', 'male')
        ->set('step', 3)
        ->call('nextStep')
        ->assertHasErrors(['pets.0.age' => 'required']);
})->with('age-required booking variants');

it('accepts not sure as a valid required pet life stage', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'vet-care', 'variant' => 'wellness-consultation'],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.requested_date', '2026-10-05')
        ->set('services.0.details', [
            'reason' => 'Routine wellness check.',
            'urgency' => 'routine',
        ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->set('step', 3)
        ->call('nextStep')
        ->assertSet('step', 4)
        ->assertHasNoErrors();
});

it('rejects an age value outside the configured pet life stages', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'vet-care', 'variant' => 'wellness-consultation'],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.requested_date', '2026-10-05')
        ->set('services.0.details', [
            'reason' => 'Routine wellness check.',
            'urgency' => 'routine',
        ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.age', 'unknown-value')
        ->set('pets.0.sex', 'male')
        ->set('step', 3)
        ->call('nextStep')
        ->assertHasErrors(['pets.0.age' => 'in']);
});

it('requires a size for an assigned boarding dog', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details', [
            'check_in' => '2026-10-05',
            'check_out' => '2026-10-08',
            'emergency_contact_primary' => 'Chidi Obi — 0808 081 1903',
            'emergency_contact_secondary' => 'Bola Obi — 0808 081 1904',
            'emergency_vet_authorization' => 'authorized',
        ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('step', 3)
        ->call('nextStep')
        ->assertHasErrors(['pets.0.size' => 'required']);
});

dataset('relocation variants', ['import', 'export']);

it('requires breed for every relocation variant', function (string $variant): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => $variant],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.requested_date', '2026-10-05')
        ->set('services.0.details', [
            'origin_country' => $variant === 'import' ? 'GH' : 'NG',
            'destination_country' => $variant === 'import' ? 'NG' : 'GH',
            'microchip_status' => 'yes',
            'documentation_status' => 'ready',
        ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->set('step', 3)
        ->call('nextStep')
        ->assertHasErrors(['pets.0.breed' => 'required']);
})->with('relocation variants');

it('does not accept removed or tiered service context', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'grooming', 'variant' => 'dogs'],
    ])
        ->assertSet('services.0.service_key', null)
        ->assertSet('services.0.service_variant', null);

    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload([
            'service_key' => 'grooming',
        ]))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('service_key');

    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload([
            'pricing_tier' => 'premium',
        ]))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('pricing_tier');
});

it('selects dog size directly and stores it on the submitted pet', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('services.0.details.check_in', now()->addDays(4)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(7)->toDateString())
        ->set('services.0.assigned_pet_ids', [0])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->set('services.0.details.emergency_contact_primary', 'Chidi Obi — 0808 081 1903')
        ->set('services.0.details.emergency_contact_secondary', 'Bola Obi — 0808 081 1904')
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'livewire@example.com')
        ->set('contact.phone', '0808 081 1902')
        ->set('step', 5)
        ->call('submit')
        ->assertSet('submitted', true);

    $bookingRequest = BookingRequest::query()->where('email', 'livewire@example.com')->firstOrFail();

    expect($bookingRequest->pets->first()->details['size'])->toBe('medium')
        ->and($bookingRequest->services->first()->quote_amount)->toBeNull()
        ->and($bookingRequest->services->first()->price_snapshot)->toBeNull();
});

it('supports relocation requests for dogs and cats without a local transport service', function (): void {
    expect(BookingRequest::serviceOptions())
        ->toHaveKeys(['boarding', 'vet-care', 'relocation'])
        ->not->toHaveKeys(['grooming', 'training', 'local-transport', 'boarding-exotic']);

    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'export'],
    ])
        ->assertSet('services.0.service_key', 'relocation')
        ->assertSet('services.0.service_variant', 'export')
        ->assertSet('services.0.details.origin_country', 'NG')
        ->assertSet('services.0.details.destination_country', null)
        ->set('step', 3)
        ->assertSee('Nnamdi Azikiwe International Airport (ABV), Abuja, Nigeria')
        ->assertSee('Choose destination country')
        ->assertSee('Search countries…')
        ->assertSee('<option value="GH">Ghana</option>', false)
        ->assertDontSee('<option value="NG">Nigeria</option>', false)
        ->assertSee('Airline or flight details')
        ->assertDontSee('Pickup details')
        ->assertDontSee('Destination details')
        ->assertDontSee('Choose origin country');
});

it('validates relocation country codes against the selected direction', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.requested_date', '2026-10-05')
        ->set('services.0.details.origin_country', 'NG')
        ->set('services.0.details.destination_country', 'GH')
        ->set('services.0.details.microchip_status', 'yes')
        ->set('services.0.details.documentation_status', 'ready')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->set('step', 3)
        ->call('nextStep')
        ->assertHasErrors(['services.0.details.origin_country' => 'in']);
});

it('clears the editable relocation country when the direction changes', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->assertSet('services.0.details.origin_country', null)
        ->assertSet('services.0.details.destination_country', 'NG')
        ->set('services.0.details.origin_country', 'GH')
        ->call('variantChanged', 0, 'export')
        ->assertSet('services.0.details.origin_country', 'NG')
        ->assertSet('services.0.details.destination_country', null);
});

it('persists a valid request as received and leaves quotation authority with staff', function (): void {
    $response = $this->followingRedirects()->post(route('booking-requests.store'), bookingRequestPayload());

    $response->assertOk()
        ->assertSee('Your request was received')
        ->assertSee('not reserved until Waggies confirms');

    $bookingRequest = BookingRequest::query()->where('email', 'ada@example.com')->firstOrFail();

    expect($bookingRequest->status)->toBe(BookingRequestStatus::New)
        ->and($bookingRequest->serviceLabel())->toBe('Boarding')
        ->and($bookingRequest->services->first()->quote_amount)->toBeNull()
        ->and($bookingRequest->services->first()->price_snapshot)->toBeNull();
});

it('persists multiple veterinary care needs in service details without creating quote lines', function (): void {
    $bookingRequest = app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'veterinary@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'cat',
            'age' => 'not-sure',
            'sex' => 'male',
        ]],
        'services' => [[
            'service_key' => 'vet-care',
            'service_variant' => null,
            'assigned_pet_ids' => [0],
            'requested_date' => '2026-10-05',
            'details' => [
                'care_needs' => ['wellness-consultation', 'vaccination-request'],
                'reason' => 'Routine check and vaccine review.',
                'urgency' => 'routine',
            ],
        ]],
    ]);

    expect($bookingRequest->services->first()->service_variant)->toBeNull()
        ->and($bookingRequest->services->first()->details['care_needs'])
        ->toBe(['wellness-consultation', 'vaccination-request'])
        ->and($bookingRequest->services->first()->quote_amount)->toBeNull()
        ->and($bookingRequest->services->first()->price_snapshot)->toBeNull();
});

it('rejects unsupported pet types and invalid booking services', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('services.0.details.check_in', now()->addDays(4)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(7)->toDateString())
        ->set('services.0.assigned_pet_ids', [0])
        ->set('pets.0.name', 'Luna')
        ->set('pets.0.species', 'other')
        ->set('pets.0.sex', 'female')
        ->set('services.0.details.emergency_contact_primary', 'Chidi Obi — 0808 081 1903')
        ->set('services.0.details.emergency_contact_secondary', 'Bola Obi — 0808 081 1904')
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'incompatible@example.com')
        ->set('contact.phone', '0808 081 1902')
        ->set('step', 5)
        ->call('submit')
        ->assertHasErrors('pets.0.species')
        ->assertSet('submitted', false);

    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload(['service_key' => 'calendar-slot']))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('service_key');
});

it('exposes booking requests and pet-size correction controls to staff', function (): void {
    config()->set('app.env', 'local');
    $bookingRequest = BookingRequest::factory()->create();
    $service = BookingRequestService::factory()->for($bookingRequest)->create();
    $pet = BookingRequestPet::factory()->for($bookingRequest)->create([
        'name' => 'Milo',
        'species' => 'dog',
        'details' => ['size' => 'medium'],
    ]);
    $service->pets()->attach($pet);

    $this->actingAs(User::factory()->create())
        ->get('/admin/booking-requests')
        ->assertOk()
        ->assertSee($bookingRequest->name);

    Livewire::test(EditBookingRequest::class, ['record' => $bookingRequest->getKey()])
        ->assertOk()
        ->assertSeeLivewire(ServicesRelationManager::class);

    Livewire::test(PetsRelationManager::class, [
        'ownerRecord' => $bookingRequest,
        'pageClass' => EditBookingRequest::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords([$pet])
        ->assertSee('Dog size');
});

it('stops adding pets after the configured eight-pet limit', function (): void {
    $component = Livewire::test('booking-request-wizard');

    foreach (range(1, 8) as $unused) {
        $component->call('addPet');
    }

    $component->assertCount('pets', 8)
        ->call('addPet')
        ->assertCount('pets', 8);
});
