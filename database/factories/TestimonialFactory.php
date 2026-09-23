<?php

namespace Database\Factories;

use App\Models\Testimonial;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Testimonial>
 */
class TestimonialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rating' => 5,
            'service' => 'grooming',
            'title' => 'A wonderful experience',
            'story' => 'The Waggies team took excellent care of our pet and kept us updated throughout the visit.',
            'author_name' => fake()->name(),
            'author_location' => 'Maitama, Abuja',
            'pet_name' => fake()->firstName(),
            'pet_type' => 'Dog',
            'photo_path' => null,
            'status' => Testimonial::STATUS_PENDING,
            'consented_at' => now(),
            'contact_method' => 'phone',
            'contact_value' => '0808 081 1902',
            'crm_match_status' => Testimonial::CRM_MATCHED,
            'identity_verification_status' => Testimonial::VERIFICATION_VERIFIED,
            'customer_relationship_status' => Testimonial::VERIFICATION_VERIFIED,
            'verification_method' => 'manual_phone',
            'verified_at' => now(),
            'published_at' => null,
            'sort_order' => 0,
        ];
    }
}
