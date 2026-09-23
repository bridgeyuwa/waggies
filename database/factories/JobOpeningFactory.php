<?php

namespace Database\Factories;

use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JobOpening>
 */
class JobOpeningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->jobTitle(),
            'department' => 'Pet Care',
            'location' => 'Abuja, Nigeria',
            'employment_type' => 'Full-time',
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'requirements' => fake()->paragraph(),
            'status' => JobOpening::STATUS_DRAFT,
            'sort_order' => 0,
        ];
    }
}
