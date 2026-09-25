<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\BookingRequestService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingRequestService>
 */
class BookingRequestServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_request_id' => BookingRequest::factory(),
            'service_key' => 'grooming',
            'service_variant' => null,
            'pricing_tier' => 'full',
            'requested_date' => fake()->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'requested_time' => fake()->optional()->time('H:i'),
            'location' => fake()->optional()->address(),
            'details' => [],
            'status' => 'pending',
            'quote_amount' => null,
            'quote_currency' => config('waggies_pricing.currency', 'NGN'),
            'quote_notes' => null,
            'status_changed_at' => null,
        ];
    }
}
