<?php

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
        'service_key' => 'grooming',
        'requested_date' => now()->addDays(7)->toDateString(),
        'requested_time' => '10:30',
        'pet_name' => 'Bruno',
        'pet_type' => 'dog',
        'location' => 'Maitama, Abuja',
        'message' => 'Bruno is nervous around loud dryers.',
        'website' => '',
    ], $overrides);
}

it('renders the public booking page with service choices and required controls', function (): void {
    $this->get(route('book'))
        ->assertOk()
        ->assertSee('Start with a request, then we will confirm the details')
        ->assertSee('Which services should we review?')
        ->assertSee('wire:model="services.0.service_key"', false)
        ->assertSee('value="boarding"', false)
        ->assertSee('Add another service');
});

it('preserves service context passed from public booking CTAs', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => [
            'service' => 'boarding',
            'variant' => 'cats',
            'tier' => 'premium',
        ],
    ])
        ->assertSet('services.0.service_key', 'boarding')
        ->assertSet('services.0.service_variant', 'cats')
        ->assertSet('services.0.pricing_tier', 'premium');
});

it('persists a valid booking request as a UUIDv7 and shows a truthful success state', function (): void {
    $response = $this->followingRedirects()->post(route('booking-requests.store'), bookingRequestPayload());

    $response->assertOk()
        ->assertSee('Your request was received')
        ->assertSee('Continue on WhatsApp')
        ->assertSee('not reserved until Waggies confirms');

    $bookingRequest = BookingRequest::query()->where('email', 'ada@example.com')->firstOrFail();

    expect($bookingRequest->getKey())
        ->toMatch('/^[0-9a-f]{8}-[0-9a-f]{4}-7[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i')
        ->and($bookingRequest->getIncrementing())->toBeFalse()
        ->and($bookingRequest->serviceLabel())->toBe('Grooming')
        ->and($bookingRequest->status)->toBe(BookingRequestStatus::New);

    $this->assertDatabaseHas('booking_requests', [
        'id' => $bookingRequest->getKey(),
        'service_key' => 'grooming',
        'pet_name' => 'Bruno',
        'status' => BookingRequestStatus::New->value,
    ]);

    $this->assertDatabaseHas('booking_request_services', [
        'booking_request_id' => $bookingRequest->getKey(),
        'service_key' => 'grooming',
        'pricing_tier' => null,
    ]);

    $this->assertDatabaseHas('booking_request_pets', [
        'booking_request_id' => $bookingRequest->getKey(),
        'name' => 'Bruno',
        'species' => 'dog',
    ]);

    expect($bookingRequest->services()->firstOrFail()->pets()->whereKey($bookingRequest->pets()->firstOrFail())->exists())->toBeTrue();

    expect($bookingRequest->requested_date->toDateString())->toBe(now()->addDays(7)->toDateString());
});

it('persists a progressive Livewire request with normalized services and pets', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'grooming'],
    ])
        ->set('services.0.requested_date', now()->addDays(4)->toDateString())
        ->set('services.0.details.coat_and_grooming_notes', 'A short trim and gentle brush-out.')
        ->set('pets.0.name', 'Milo')
        ->set('pets.0.species', 'dog')
        ->set('pets.0.breed', 'Mixed breed')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'livewire@example.com')
        ->set('contact.phone', '0808 081 1902')
        ->set('contact.preferred_contact_method', 'whatsapp')
        ->set('step', 5)
        ->call('submit')
        ->assertSet('submitted', true);

    $bookingRequest = BookingRequest::query()->where('email', 'livewire@example.com')->firstOrFail();

    expect($bookingRequest->services)->toHaveCount(1)
        ->and($bookingRequest->pets)->toHaveCount(1)
        ->and($bookingRequest->services->first()->pets->first()->is($bookingRequest->pets->first()))->toBeTrue();
});

it('rejects invalid booking input without persisting a request', function (): void {
    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload([
            'email' => 'not-an-email',
            'requested_date' => now()->subDay()->toDateString(),
            'pet_type' => 'dragon',
        ]))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors(['email', 'requested_date', 'pet_type']);

    expect(BookingRequest::query()->count())->toBe(0);
});

it('rejects an invalid service identifier without persisting a request', function (): void {
    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload(['service_key' => 'calendar-slot']))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('service_key');

    expect(BookingRequest::query()->count())->toBe(0);
});

it('rejects honeypot submissions without persisting a request', function (): void {
    $this->from(route('book'))
        ->post(route('booking-requests.store'), bookingRequestPayload(['website' => 'https://spam.example']))
        ->assertRedirect(route('book'))
        ->assertSessionHasErrors('website');

    expect(BookingRequest::query()->count())->toBe(0);
});

it('rate limits repeated booking requests from the same email and address', function (): void {
    foreach (range(1, 5) as $attempt) {
        $this->post(route('booking-requests.store'), bookingRequestPayload(['name' => "Ada Obi {$attempt}"]));
    }

    $this->post(route('booking-requests.store'), bookingRequestPayload(['name' => 'Ada Obi 6']))
        ->assertTooManyRequests();

    expect(BookingRequest::query()->count())->toBe(5);
});

it('exposes booking requests to authenticated Filament staff and persists status changes', function (): void {
    config()->set('app.env', 'local');
    $bookingRequest = BookingRequest::factory()->create();

    $this->actingAs(User::factory()->create())
        ->get('/admin/booking-requests')
        ->assertOk()
        ->assertSee($bookingRequest->name)
        ->assertSee($bookingRequest->serviceLabel());

    $bookingRequest->update(['status' => BookingRequestStatus::Confirmed]);

    expect($bookingRequest->fresh()->status)->toBe(BookingRequestStatus::Confirmed);
    $this->assertDatabaseHas('booking_requests', [
        'id' => $bookingRequest->getKey(),
        'status' => BookingRequestStatus::Confirmed->value,
    ]);
});

it('shows normalized services and pets in the Filament booking request view', function (): void {
    config()->set('app.env', 'local');
    $bookingRequest = BookingRequest::factory()->create();
    $service = BookingRequestService::factory()->for($bookingRequest)->create([
        'service_key' => 'grooming',
        'service_variant' => null,
        'pricing_tier' => 'full',
    ]);
    $pet = BookingRequestPet::factory()->for($bookingRequest)->create([
        'name' => 'Milo',
        'species' => 'dog',
    ]);
    $service->pets()->attach($pet);

    $this->actingAs(User::factory()->create());

    Livewire::test(EditBookingRequest::class, ['record' => $bookingRequest->getKey()])
        ->assertOk()
        ->assertSeeLivewire(ServicesRelationManager::class)
        ->assertSee('Pets');

    Livewire::test(ServicesRelationManager::class, [
        'ownerRecord' => $bookingRequest,
        'pageClass' => EditBookingRequest::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords([$service]);

    Livewire::test(PetsRelationManager::class, [
        'ownerRecord' => $bookingRequest,
        'pageClass' => EditBookingRequest::class,
    ])
        ->assertOk()
        ->assertCanSeeTableRecords([$pet]);
});

it('does not expose private booking details on the public page', function (): void {
    $bookingRequest = BookingRequest::factory()->create([
        'email' => 'private-owner@example.com',
        'phone' => '0800 000 0000',
        'message' => 'Private operational note.',
    ]);

    $this->get(route('book'))
        ->assertOk()
        ->assertDontSee($bookingRequest->email)
        ->assertDontSee($bookingRequest->phone)
        ->assertDontSee($bookingRequest->message);
});
