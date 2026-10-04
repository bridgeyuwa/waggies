<?php

use App\Actions\CreateBookingRequest;
use App\Enums\BookingRequestStatus;
use App\Filament\Resources\BookingRequests\Pages\EditBookingRequest;
use App\Filament\Resources\BookingRequests\RelationManagers\PetsRelationManager;
use App\Filament\Resources\BookingRequests\RelationManagers\ServicesRelationManager;
use App\Mail\NewBookingRequest;
use App\Models\BookingRequest;
use App\Models\BookingRequestPet;
use App\Models\BookingRequestService;
use App\Models\BusinessProfile;
use App\Models\User;
use App\Support\BookingRequestSchema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
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

it('renders a draft-persisted submission token for retry-safe booking requests', function (): void {
    $this->get(route('book'))
        ->assertOk()
        ->assertSee('wire:model.live="submissionToken"', false)
        ->assertSee('data-booking-draft-model="submissionToken"', false);
});

it('derives the booking start step from valid semantic URL context', function (): void {
    $this->get(route('book', ['service' => 'boarding', 'pet_type' => 'dog']))
        ->assertOk()
        ->assertSee('STEP 2 OF 5')
        ->assertSee('value="dog"', false);

    $this->get(route('book', ['service' => 'vet-care', 'care_need' => 'microchip']))
        ->assertOk()
        ->assertSee('STEP 2 OF 5')
        ->assertSee('Microchip implantation');

    $this->get(route('book', ['service' => 'relocation', 'direction' => 'export']))
        ->assertOk()
        ->assertSee('STEP 2 OF 5')
        ->assertSee('Export from Nigeria');
});

it('preserves valid URL context while safely falling back for invalid parts', function (): void {
    $this->get(route('book', ['service' => 'boarding', 'pet_type' => 'unicorn']))
        ->assertOk()
        ->assertSee('STEP 1 OF 5')
        ->assertSee('We could not use the pet type in that link');

    $this->get(route('book', ['service' => 'boarding-dogs']))
        ->assertOk()
        ->assertSee('STEP 1 OF 5')
        ->assertSee('That booking link is no longer available');
});

it('restores a complete draft through the guarded Livewire boundary', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding'],
    ])
        ->assertSet('draftContextKey', 'boarding|||||')
        ->call('restoreDraft', [
            'contextKey' => 'boarding|||||',
            'step' => 4,
            'submissionToken' => 'draft-token',
            'services' => [[
                'service_key' => 'boarding',
                'service_variant' => 'dogs',
                'assigned_pet_ids' => [0],
                'details' => [],
            ]],
            'pets' => [[
                'name' => 'Bruno',
                'species' => 'dog',
                'sex' => 'male',
            ]],
            'contact' => [
                'name' => 'Ada Obi',
                'email' => 'ada@example.com',
                'phone_country' => 'NG',
            ],
        ])
        ->assertSet('step', 4)
        ->assertSet('submissionToken', 'draft-token')
        ->assertSet('pets.0.name', 'Bruno')
        ->assertSet('contact.name', 'Ada Obi');
});

it('clears the booking request and keeps the request notice visible', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'pet_type' => 'dog'],
    ])
        ->set('step', 4)
        ->set('pets.0.name', 'Bruno')
        ->set('contact.name', 'Ada Obi')
        ->call('clearBooking')
        ->assertSet('step', 1)
        ->assertSet('services.0.service_key', null)
        ->assertSet('pets.0.name', null)
        ->assertSet('contact.name', null)
        ->assertSee('This is a request, not a confirmed booking.')
        ->assertSee('Start over')
        ->assertSee('Any information you’ve entered will be cleared, and you’ll return to Step 1.')
        ->assertDontSee('wire:confirm', false);
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

it('allows multiple services to be selected upfront and removes only the deselected service', function (): void {
    Livewire::test('booking-request-wizard')
        ->call('toggleService', 'boarding')
        ->call('toggleService', 'relocation')
        ->assertSee('Configure Boarding')
        ->assertSee('Configure Relocation')
        ->call('toggleService', 'boarding')
        ->assertSet('services.0.service_key', 'relocation')
        ->assertDontSee('Configure Boarding')
        ->assertSee('Configure Relocation');
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

it('shows only assigned pets in the review summary', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding'],
    ])
        ->call('addPet')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('pets.1.name', 'Luna')
        ->set('pets.1.species', 'cat')
        ->set('pets.1.sex', 'female')
        ->set('services.0.assigned_pet_ids', [0])
        ->set('step', 5)
        ->assertSee('Milo')
        ->assertDontSee('Luna · Cat')
        ->assertDontSee('Not assigned yet');
});

it('uses neutral prompts and explicit choices for low-option booking fields', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('step', 2)
        ->assertSee('Pet type')
        ->assertSee('type="radio" name="booking-pet-0-species"', false)
        ->assertSee('Dog')
        ->assertSee('Cat')
        ->assertSee('<option value="">Select life stage</option>', false)
        ->assertSee('Sex')
        ->assertSee('type="radio" name="booking-pet-0-sex"', false)
        ->assertSee('Male')
        ->assertSee('Female')
        ->set('step', 3)
        ->assertDontSee('Primary emergency contact')
        ->assertSee('I authorize emergency veterinary care if needed')
        ->assertSee('Please discuss this with me during review')
        ->assertDontSee('<option value="">Choose an option</option>', false);
});

it('uses human field labels in the booking error summary', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('pets.0.name', 'Bruno')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.emergency_vet_authorization', null)
        ->set('step', 3)
        ->call('nextStep')
        ->assertSee('The emergency veterinary authorization field is required.');
});

it('uses human field labels when a service has no assigned pet', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('pets.0.name', 'Bruno')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->set('services.0.details.check_in', now()->addDays(7)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(9)->toDateString())
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('step', 3)
        ->call('nextStep')
        ->assertHasErrors(['services.0.assigned_pet_ids' => 'required'])
        ->assertSee('The assigned pet field is required.');

    preg_match('/<div id="booking-error-summary".*?<\/div>/s', $component->html(), $matches);

    expect($matches[0] ?? '')
        ->toContain('The assigned pet field is required.')
        ->not->toContain('services.0.assigned_pet_ids');
});

it('uses neutral prompts for relocation country selectors', function (): void {
    $fields = BookingRequestSchema::serviceFields('relocation', 'import');

    expect(collect($fields)->firstWhere('key', 'origin_country')['placeholder'])->toBe('Select origin country')
        ->and(collect($fields)->firstWhere('key', 'destination_country')['placeholder'])->toBe('Select destination country');
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

it('requires age or life stage before leaving the pet step for age-dependent services', function (string $service, string $variant): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => $service, 'variant' => $variant],
    ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('step', 2)
        ->call('nextStep')
        ->assertSet('step', 2)
        ->assertHasErrors(['pets.0.age' => 'required']);
})->with([
    'veterinary care' => ['vet-care', 'wellness-consultation'],
    'relocation' => ['relocation', 'import'],
]);

it('explains the age requirement on the pet step before service assignment', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'vet-care', 'variant' => 'wellness-consultation'],
    ])
        ->set('step', 2)
        ->assertSee('Required for veterinary care or relocation. Choose Not sure if you do not know.');
});

it('keeps age optional on the pet step for boarding-only requests', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('step', 2)
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertHasNoErrors();
});

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
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->set('services.0.assigned_pet_ids', [0])
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

it('accepts the simple POST contract for fixed-rate cat boarding', function (): void {
    $response = $this->followingRedirects()->post(route('booking-requests.store'), bookingRequestPayload([
        'email' => 'cat-boarding@example.com',
        'pet_name' => 'Luna',
        'pet_type' => 'cat',
        'service_variant' => null,
    ]));

    $response->assertOk()
        ->assertSee('Your request was received');

    $bookingRequest = BookingRequest::query()->where('email', 'cat-boarding@example.com')->firstOrFail();

    expect($bookingRequest->pets->first()->species)->toBe('cat')
        ->and($bookingRequest->services->first()->service_variant)->toBeNull()
        ->and($bookingRequest->services->first()->quote_amount)->toBeNull()
        ->and($bookingRequest->services->first()->price_snapshot)->toBeNull();
});

it('replays an idempotent booking request without creating a duplicate', function (): void {
    $payload = [
        'idempotency_key' => 'booking-replay-test-1',
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'idempotent@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
        ]],
        'services' => [[
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => [0],
            'details' => [
                'check_in' => now()->addDays(7)->toDateString(),
                'check_out' => now()->addDays(8)->toDateString(),
                'emergency_contact_primary' => 'Chidi Obi — 0808 081 1903',
                'emergency_vet_authorization' => 'authorized',
            ],
        ]],
    ];

    $first = app(CreateBookingRequest::class)->handle($payload);
    $second = app(CreateBookingRequest::class)->handle($payload);

    expect($second->getKey())->toBe($first->getKey())
        ->and(BookingRequest::query()->count())->toBe(1)
        ->and($second->pets)->toHaveCount(1)
        ->and($second->services)->toHaveCount(1);
});

it('allows relocation windows with different end dates in one request', function (): void {
    $bookingRequest = app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'relocation-windows@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
            'breed' => 'Mixed breed',
            'age' => 'not-sure',
            'sex' => 'male',
        ]],
        'services' => [
            [
                'service_key' => 'relocation',
                'service_variant' => 'export',
                'assigned_pet_ids' => [0],
                'requested_date' => '2026-10-10',
                'requested_end_date' => '2026-10-14',
                'details' => [
                    'travel_timing' => 'window',
                    'origin_country' => 'NG',
                    'destination_country' => 'GH',
                    'flight_status' => 'not_booked',
                    'microchip_status' => 'yes',
                    'documentation_status' => 'ready',
                ],
            ],
            [
                'service_key' => 'relocation',
                'service_variant' => 'export',
                'assigned_pet_ids' => [0],
                'requested_date' => '2026-10-10',
                'requested_end_date' => '2026-10-21',
                'details' => [
                    'travel_timing' => 'window',
                    'origin_country' => 'NG',
                    'destination_country' => 'GH',
                    'flight_status' => 'not_booked',
                    'microchip_status' => 'yes',
                    'documentation_status' => 'ready',
                ],
            ],
        ],
    ]);

    expect($bookingRequest->services)->toHaveCount(2)
        ->and($bookingRequest->services->pluck('requested_end_date')->map->toDateString()->all())
        ->toBe(['2026-10-14', '2026-10-21']);
});

it('replays a submitted Livewire request without creating a duplicate', function (): void {
    Mail::fake();
    BusinessProfile::query()->firstOrFail()->update(['primary_email' => 'operations@waggies.test']);

    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.size', 'medium')
        ->set('pets.0.sex', 'male')
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details.check_in', '2026-10-10')
        ->set('services.0.details.check_out', '2026-10-12')
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'livewire-replay@example.com')
        ->set('contact.phone_country', 'NG')
        ->set('contact.phone_number', '08080811902')
        ->call('submit')
        ->assertSet('submitted', true);

    $component
        ->call('submit')
        ->assertSet('submitted', true);

    expect(BookingRequest::query()->where('email', 'livewire-replay@example.com')->count())->toBe(1);
});

it('queues a staff notification only when a booking request is newly created', function (): void {
    Mail::fake();
    BusinessProfile::query()->firstOrFail()->update(['primary_email' => 'operations@waggies.test']);

    $bookingRequest = app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'notification@example.com',
            'phone_country' => 'NG',
            'phone_number' => '0808 081 1902',
            'preferred_contact_method' => 'whatsapp',
        ],
        'pets' => [[
            'name' => 'Bruno',
            'species' => 'dog',
        ]],
        'services' => [[
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => [0],
            'requested_date' => now()->addDays(7)->toDateString(),
        ]],
    ]);

    Mail::assertQueued(NewBookingRequest::class, function (NewBookingRequest $mail) use ($bookingRequest): bool {
        return $mail->bookingRequest->is($bookingRequest);
    });
});

it('rejects reusing an idempotency key for different booking details', function (): void {
    $payload = [
        'idempotency_key' => 'booking-replay-test-2',
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'idempotent-first@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
        ]],
        'services' => [[
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => [0],
            'details' => [
                'check_in' => now()->addDays(7)->toDateString(),
                'check_out' => now()->addDays(8)->toDateString(),
                'emergency_contact_primary' => 'Chidi Obi — 0808 081 1903',
                'emergency_vet_authorization' => 'authorized',
            ],
        ]],
    ];

    app(CreateBookingRequest::class)->handle($payload);

    expect(fn (): BookingRequest => app(CreateBookingRequest::class)->handle([
        ...$payload,
        'contact' => [
            ...$payload['contact'],
            'email' => 'idempotent-second@example.com',
        ],
    ]))->toThrow(ValidationException::class);

    expect(BookingRequest::query()->count())->toBe(1);
});

it('replays the legacy POST contract when an idempotency header is reused', function (): void {
    $payload = bookingRequestPayload(['email' => 'legacy-idempotent@example.com']);

    $this->withHeader('Idempotency-Key', 'legacy-post-replay-test')
        ->post(route('booking-requests.store'), $payload)
        ->assertRedirect(route('book'));

    $this->withHeader('Idempotency-Key', 'legacy-post-replay-test')
        ->post(route('booking-requests.store'), $payload)
        ->assertRedirect(route('book'));

    expect(BookingRequest::query()->where('email', 'legacy-idempotent@example.com')->count())->toBe(1);
});

it('uses the selected calling code and removes secondary emergency contact data', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('step', 3)
        ->assertDontSee('Secondary emergency contact');

    $bookingRequest = app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'phone-country@example.com',
            'phone_country' => 'GH',
            'phone_number' => '024 123 4567',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
        ]],
        'services' => [[
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => [0],
            'details' => [
                'emergency_contact_primary' => 'Chidi Obi — 0808 081 1903',
                'emergency_contact_secondary' => 'Bola Obi — 0808 081 1904',
            ],
        ]],
    ]);

    expect($bookingRequest->phone)->toBe('+233241234567')
        ->and($bookingRequest->services->first()->details)->not->toHaveKey('emergency_contact_secondary');
});

it('rejects an invalid international phone number in the simple POST contract', function (): void {
    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload([
            'phone' => null,
            'phone_country' => 'NG',
            'phone_number' => '0808 081 19',
        ]))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('phone_number');

    expect(BookingRequest::query()->where('email', 'ada@example.com')->exists())->toBeFalse();
});

it('preserves the selected international calling code in the simple POST contract', function (): void {
    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload([
            'email' => 'ghana-post@example.com',
            'phone' => null,
            'phone_country' => 'GH',
            'phone_number' => '024 123 4567',
        ]))
        ->assertRedirect(route('book'))
        ->assertSessionHas('booking_submitted', true);

    expect(BookingRequest::query()->where('email', 'ghana-post@example.com')->value('phone'))
        ->toBe('+233241234567');
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

it('normalizes sparse pet and service indexes before persisting assignments', function (): void {
    $bookingRequest = app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'sparse@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [2 => [
            'name' => 'Milo',
            'species' => 'dog',
            'age' => 'not-sure',
            'sex' => 'male',
        ]],
        'services' => [4 => [
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => ['0'],
        ]],
    ]);

    expect($bookingRequest->pets->pluck('name')->all())->toBe(['Milo'])
        ->and($bookingRequest->services->first()->pets->pluck('name')->all())->toBe(['Milo']);
});

it('rejects malformed pet assignment indexes before persisting a request', function (): void {
    expect(fn (): BookingRequest => app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'malformed-assignment@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
        ]],
        'services' => [[
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => ['not-an-index'],
        ]],
    ]))->toThrow(ValidationException::class);

    expect(BookingRequest::query()->where('email', 'malformed-assignment@example.com')->exists())->toBeFalse();
});

it('rejects duplicate pet assignments before hitting the pivot primary key', function (): void {
    expect(fn (): BookingRequest => app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'duplicate-assignment@example.com',
            'phone' => '0808 081 1902',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
        ]],
        'services' => [[
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'assigned_pet_ids' => [0, 0],
        ]],
    ]))->toThrow(ValidationException::class);

    expect(BookingRequest::query()->where('email', 'duplicate-assignment@example.com')->exists())->toBeFalse();
});

it('ignores stale wizard mutation indexes without corrupting draft state', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding'],
    ])
        ->call('addPet')
        ->set('services.0.assigned_pet_ids', [1])
        ->call('removePet', -1)
        ->assertCount('pets', 2)
        ->assertSet('services.0.assigned_pet_ids', [1])
        ->call('serviceChanged', 99, 'boarding')
        ->assertCount('services', 1);
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

it('returns the user to pet details when a required dog size is missing', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('step', 3)
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.sex', 'male')
        ->set('services.0.assigned_pet_ids', [0])
        ->call('nextStep')
        ->assertHasErrors('pets.0.size')
        ->assertSet('step', 2);
});

it('exposes read-only booking request facts and service review controls to staff', function (): void {
    config()->set('app.env', 'local');
    $bookingRequest = BookingRequest::factory()->create();
    $service = BookingRequestService::factory()->for($bookingRequest)->create();
    $pet = BookingRequestPet::factory()->for($bookingRequest)->create([
        'name' => 'Milo',
        'species' => 'dog',
        'details' => ['size' => 'medium'],
    ]);
    $service->pets()->attach($pet);

    $this->actingAs(User::factory()->admin()->create())
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
        ->assertSee('Dog size')
        ->assertTableActionDoesNotExist('edit', record: $pet);
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
