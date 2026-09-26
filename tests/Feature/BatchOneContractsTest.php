<?php

namespace Tests\Feature;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use App\Models\BusinessHour;
use App\Models\BusinessProfile;
use App\Models\User;
use DomainException;
use Tests\TestCase;

class BatchOneContractsTest extends TestCase
{
    public function test_booking_request_persists_context_and_enforces_status_transitions(): void
    {
        $booking = BookingRequest::factory()->create([
            'service_key' => 'boarding',
            'service_variant' => 'dogs',
            'pricing_tier' => 'standard',
            'source' => 'pricing',
            'context' => ['estimate' => ['min' => 10000, 'max' => 15000]],
        ]);

        $this->assertSame(BookingRequestStatus::New, $booking->status);
        $this->assertSame('pricing', $booking->source);
        $this->assertSame(['estimate' => ['min' => 10000, 'max' => 15000]], $booking->context);

        $booking->transitionTo(BookingRequestStatus::Reviewing);
        $this->assertSame(BookingRequestStatus::Reviewing, $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->status_changed_at);

        $this->expectException(DomainException::class);
        $booking->transitionTo(BookingRequestStatus::New);
    }

    public function test_business_profile_and_hours_are_canonical_public_sources(): void
    {
        $profile = BusinessProfile::current();

        $this->assertSame('Waggies', $profile->business_name);
        $this->assertNotEmpty($profile->phone);
        $this->assertCount(7, BusinessHour::publicSchedule());
        $this->assertSame('Monday', BusinessHour::publicSchedule()[0]['day']);
        $this->assertNotEmpty($profile->toPublicArray()['mapUrl']);
        $this->assertArrayNotHasKey('latitude', $profile->toPublicArray());
        $this->assertArrayNotHasKey('longitude', $profile->toPublicArray());
    }

    public function test_business_hour_exception_overrides_the_weekly_schedule_on_public_pages(): void
    {
        $this->travelTo('2026-09-21 12:00:00');

        BusinessHour::query()->create([
            'kind' => BusinessHour::KIND_EXCEPTION,
            'date' => '2026-09-21',
            'is_closed' => true,
            'label' => 'Public holiday closure',
        ]);

        $this->get(route('contact'))
            ->assertSee('Monday')
            ->assertSee('Closed');
    }

    public function test_batch_one_admin_resources_render_for_staff(): void
    {
        config()->set('app.env', 'local');
        $this->actingAs(User::factory()->create());

        $this->get('/admin/booking-requests')->assertOk();
        $this->get('/admin/business-profiles')->assertOk();
        $this->get('/admin/business-hours')->assertOk();
        $this->get('/admin/job-openings')->assertOk();
        $this->get('/admin/products')->assertOk();
        $this->get('/admin/testimonials')->assertOk();
    }
}
