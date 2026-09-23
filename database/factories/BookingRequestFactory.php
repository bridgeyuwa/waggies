<?php

namespace Database\Factories;

use App\Enums\BookingRequestStatus;
use App\Models\BookingRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingRequest>
 */
class BookingRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '0808 081 1902',
            'preferred_contact_method' => 'whatsapp',
            'service_key' => 'grooming',
            'source' => 'factory',
            'requested_date' => now()->addDays(7)->toDateString(),
            'requested_time' => '10:00',
            'pet_name' => fake()->firstName(),
            'pet_type' => 'dog',
            'location' => 'Maitama, Abuja',
            'message' => 'Please let us know what to bring.',
            'status' => BookingRequestStatus::New,
            'status_changed_at' => now(),
        ];
    }
}
