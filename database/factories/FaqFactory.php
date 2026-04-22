<?php

namespace Database\Factories;

use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    public function definition(): array
    {
        $categories = ['boarding', 'grooming', 'relocation', 'vet-care', 'general'];

        return [
            'category'   => fake()->randomElement($categories),
            'question'   => fake()->sentence(8, true) . '?',
            'answer'     => fake()->paragraph(3),
            'sort_order' => fake()->numberBetween(0, 100),
            'is_active'  => true,
        ];
    }
}
