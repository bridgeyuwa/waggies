<?php

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
        ->assertSee('Send booking request')
        ->assertSee('name="service_key"', false)
        ->assertSee('value="boarding"', false)
        ->assertSee('name="requested_date"', false)
        ->assertSee('name="pet_name"', false);
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

    expect($bookingRequest->requested_date->toDateString())->toBe(now()->addDays(7)->toDateString());
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
