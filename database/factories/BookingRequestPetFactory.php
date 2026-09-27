<?php

namespace Database\Factories;

use App\Models\BookingRequest;
use App\Models\BookingRequestPet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookingRequestPet>
 */
class BookingRequestPetFactory extends Factory
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
            'name' => fake()->firstName(),
            'species' => fake()->randomElement(['dog', 'cat', 'bird', 'rabbit', 'reptile', 'other']),
            'breed' => fake()->optional()->word(),
            'age' => fake()->optional()->randomElement(['puppy', 'adult', 'senior']),
            'sex' => fake()->optional()->randomElement(['female', 'male']),
            'notes' => fake()->optional()->sentence(),
            'details' => [],
        ];
    }
}
