<?php

namespace Database\Factories;

use App\Models\ContactEnquiry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactEnquiry>
 */
class ContactEnquiryFactory extends Factory
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
            'phone' => fake()->phoneNumber(),
            'subject' => 'General enquiry',
            'service' => null,
            'intent' => 'GENERAL_INQUIRY',
            'message' => fake()->paragraph(),
            'reference' => 'WGX-'.fake()->bothify('####'),
            'status' => ContactEnquiry::STATUS_NEW,
            'received_at' => now(),
        ];
    }
}
