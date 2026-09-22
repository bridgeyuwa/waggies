<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => 'general',
            'subcategory' => null,
            'question' => fake()->sentence(),
            'answer' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(0, 100),
            'status' => Faq::STATUS_DRAFT,
            'published_at' => null,
        ];
    }
}
