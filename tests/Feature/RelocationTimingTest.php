<?php

use App\Actions\CreateBookingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

dataset('relocation directions', ['import', 'export']);

function fillRelocationTimingDetails(object $component, string $variant): object
{
    return $component
        ->set('services.0.assigned_pet_ids', [0])
        ->set('services.0.details.origin_country', $variant === 'import' ? 'GH' : 'NG')
        ->set('services.0.details.destination_country', $variant === 'import' ? 'NG' : 'GH')
        ->set('services.0.details.microchip_status', 'yes')
        ->set('services.0.details.documentation_status', 'ready')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('pets.0.age', 'not-sure')
        ->set('pets.0.sex', 'male')
        ->set('step', 3);
}

it('allows import and export requests without a fixed date or booked flight', function (string $variant): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => $variant],
    ]);

    fillRelocationTimingDetails($component, $variant)
        ->set('services.0.details.travel_timing', 'not_decided')
        ->set('services.0.details.flight_status', 'not_booked')
        ->call('nextStep')
        ->assertSet('step', 4)
        ->assertHasNoErrors();
})->with('relocation directions');

it('requires an exact travel date when the client says the date is known', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ]);

    fillRelocationTimingDetails($component, 'import')
        ->set('services.0.details.travel_timing', 'exact')
        ->set('services.0.details.flight_status', 'booked')
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertHasErrors(['services.0.requested_date' => 'required'])
        ->set('services.0.requested_date', now()->addDays(14)->toDateString())
        ->call('nextStep')
        ->assertSet('step', 4)
        ->assertHasNoErrors();
});

it('accepts an approximate relocation window and requires its dates in order', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'export'],
    ]);

    fillRelocationTimingDetails($component, 'export')
        ->set('services.0.details.travel_timing', 'window')
        ->set('services.0.details.flight_status', 'not_booked')
        ->set('services.0.requested_date', now()->addDays(21)->toDateString())
        ->set('services.0.requested_end_date', now()->addDays(14)->toDateString())
        ->call('nextStep')
        ->assertSet('step', 3)
        ->assertHasErrors(['services.0.requested_end_date' => 'after'])
        ->set('services.0.requested_end_date', now()->addDays(28)->toDateString())
        ->call('nextStep')
        ->assertSet('step', 4)
        ->assertHasNoErrors();
});

it('clears dates that no longer apply when relocation timing changes', function (): void {
    $component = Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ]);

    $component
        ->set('services.0.details.travel_timing', 'window')
        ->set('services.0.requested_date', now()->addDays(14)->toDateString())
        ->set('services.0.requested_end_date', now()->addDays(21)->toDateString())
        ->set('services.0.details.travel_timing', 'exact')
        ->assertSet('services.0.requested_end_date', null)
        ->set('services.0.details.travel_timing', 'not_decided')
        ->assertSet('services.0.requested_date', null)
        ->assertSet('services.0.requested_end_date', null);
});

it('renders airline and flight details as a multiline field', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'relocation', 'variant' => 'import'],
    ])
        ->set('step', 3)
        ->assertSee('<textarea id="booking-0-airline_airport_details"', false)
        ->assertDontSee('<input id="booking-0-airline_airport_details"', false);
});

it('preserves unknown relocation timing instead of inventing a requested date', function (): void {
    $booking = app(CreateBookingRequest::class)->handle([
        'contact' => [
            'name' => 'Ada Obi',
            'email' => 'ada@example.com',
            'phone' => '0808 081 1902',
            'phone_country' => 'NG',
            'phone_number' => '08080811902',
            'phone_other_country_code' => null,
            'preferred_contact_method' => 'whatsapp',
        ],
        'pets' => [[
            'name' => 'Milo',
            'species' => 'dog',
            'breed' => 'Mixed breed',
            'age' => 'not-sure',
            'sex' => 'male',
        ]],
        'services' => [[
            'service_key' => 'relocation',
            'service_variant' => 'import',
            'assigned_pet_ids' => [0],
            'requested_date' => null,
            'requested_end_date' => null,
            'details' => [
                'travel_timing' => 'not_decided',
                'flight_status' => 'not_booked',
                'origin_country' => 'GH',
                'destination_country' => 'NG',
                'microchip_status' => 'yes',
                'documentation_status' => 'ready',
            ],
        ]],
    ]);

    $service = $booking->services()->firstOrFail();

    expect($booking->requested_date)->toBeNull()
        ->and($service->requested_date)->toBeNull()
        ->and($service->requested_end_date)->toBeNull()
        ->and($service->details)->toMatchArray([
            'travel_timing' => 'not_decided',
            'flight_status' => 'not_booked',
        ]);
});
