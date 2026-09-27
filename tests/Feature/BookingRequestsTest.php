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

it('preserves active service context without a tier', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'cats'],
    ])
        ->assertSet('services.0.service_key', 'boarding')
        ->assertSet('services.0.service_variant', 'cats')
        ->assertSee('Cats');
});

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
        ->set('step', 3)
        ->assertSee('Origin country')
        ->assertSee('Destination country');
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

it('rejects incompatible pets and invalid booking services', function (): void {
    Livewire::test('booking-request-wizard', [
        'initialContext' => ['service' => 'boarding', 'variant' => 'dogs'],
    ])
        ->set('services.0.details.check_in', now()->addDays(4)->toDateString())
        ->set('services.0.details.check_out', now()->addDays(7)->toDateString())
        ->set('services.0.assigned_pet_ids', [0])
        ->set('pets.0.name', 'Luna')
        ->set('pets.0.species', 'cat')
        ->set('pets.0.sex', 'female')
        ->set('services.0.details.emergency_contact_primary', 'Chidi Obi — 0808 081 1903')
        ->set('services.0.details.emergency_contact_secondary', 'Bola Obi — 0808 081 1904')
        ->set('services.0.details.emergency_vet_authorization', 'authorized')
        ->set('contact.name', 'Ada Obi')
        ->set('contact.email', 'incompatible@example.com')
        ->set('contact.phone', '0808 081 1902')
        ->set('step', 5)
        ->call('submit')
        ->assertHasErrors('services.0.assigned_pet_ids')
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
