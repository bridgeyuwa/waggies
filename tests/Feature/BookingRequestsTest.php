<?php

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
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
        'message' => 'Bruno needs a calm handover.',
        'website' => '',
    ], $overrides);
}

it('renders the booking request flow with only active services and no package controls', function (): void {
    $this->get(route('book'))
        ->assertOk()
        ->assertSee('Submit Booking Request')
        ->assertSee('Boarding')
        ->assertSee('Veterinary Care')
        ->assertSee('Relocation')
        ->assertSee('Choose your services')
        ->assertSee('data-booking-draft="waggies-booking-request-v3"', false)
        ->assertDontSee('Grooming')
        ->assertDontSee('Dog Training')
        ->assertDontSee('Local Transport')
        ->assertDontSee('Choose a package')
        ->assertDontSee('Weight');
});

it('normalizes retired booking contexts to an empty active request', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'local-transport', 'tier' => 'airport'],
    ])
        ->assertSet('services.0.service_key', null)
        ->assertSet('services.0.service_variant', null)
        ->assertSet('services.0.pricing_tier', null);
});

it('supports direct dog size selection without weight input', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('services.0.service_variant', 'dogs')
        ->call('nextStep')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->call('nextStep')
        ->set('services.0.assigned_pet_ids', [0])
        ->set('pets.0.size', 'medium')
        ->set('services.0.details.check_in', now()->addDays(4)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(7)->toDateString())
        ->set('services.0.details.feeding_requirements', 'Owner-supplied food')
        ->set('services.0.details.emergency_contact_primary', 'Primary Contact 0800 000 0000')
        ->set('services.0.details.emergency_contact_secondary', 'Secondary Contact 0800 000 0001')
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->call('nextStep')
        ->assertSet('step', 4)
        ->assertDontSee('Weight');
});

it('uses one cat boarding option and no boarding tier selection', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'cats'],
    ])
        ->assertSee('Cats')
        ->assertDontSee('Basic package')
        ->assertDontSee('Premium package')
        ->assertDontSee('Choose a tier');
});

it('persists a simple request without automatic quote authority', function (): void {
    $this->followingRedirects()
        ->post(route('booking-requests.store'), bookingRequestPayload())
        ->assertOk()
        ->assertSee('Your request was received')
        ->assertSee('not reserved until Waggies confirms it');

    $bookingRequest = BookingRequest::query()->where('email', 'ada@example.com')->firstOrFail();

    expect($bookingRequest->serviceLabel())->toBe('Boarding')
        ->and($bookingRequest->status)->toBe(BookingRequestStatus::New)
        ->and($bookingRequest->quote_amount)->toBeNull()
        ->and($bookingRequest->services->first()->pricing_tier)->toBeNull()
        ->and($bookingRequest->services->first()->quote_amount)->toBeNull()
        ->and($bookingRequest->services->first()->price_snapshot)->toBeNull();
});

it('rejects retired service and non-canine-or-feline requests', function (): void {
    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload(['service_key' => 'grooming']))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('service_key');

    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload(['pet_type' => 'exotic']))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('pet_type');
});

it('keeps relocation requests quote-only and limited to dogs and cats', function (): void {
    $this->post(route('booking-requests.store'), bookingRequestPayload([
        'service_key' => 'relocation',
        'service_variant' => 'import',
    ]))->assertRedirect(route('book'));

    $request = BookingRequest::query()->where('email', 'ada@example.com')->firstOrFail();

    expect($request->service_variant)->toBe('import')
        ->and($request->quote_amount)->toBeNull();
});

it('supports the complete request status workflow through staff review', function (): void {
    $booking = BookingRequest::factory()->create();

    $booking->transitionTo(BookingRequestStatus::Reviewing);
    $booking->transitionTo(BookingRequestStatus::Quoted);
    $booking->transitionTo(BookingRequestStatus::AwaitingCustomer);
    $booking->transitionTo(BookingRequestStatus::Confirmed);
    $booking->transitionTo(BookingRequestStatus::Completed);

    expect($booking->fresh()->status)->toBe(BookingRequestStatus::Completed)
        ->and(BookingRequestStatus::options())->toHaveKey('completed');
});

it('limits the number of pets in a request', function (): void {
    $component = Livewire::test('booking-request-wizard');

    foreach (range(1, 8) as $unused) {
        $component->call('addPet');
    }

    $component->assertCount('pets', 8)
        ->call('addPet')
        ->assertCount('pets', 8);
});
