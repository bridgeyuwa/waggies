<?php

namespace Database\Factories;

use App\Models\KbArticle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<KbArticle>
 */
class KbArticleFactory extends Factory
{
    private const CATEGORIES = [
        'boarding', 'grooming', 'vet-care', 'training', 'transport', 'relocation', 'loyalty-programme', 'billing',
    ];

    public function definition(): array
    {
        $title = $this->faker->sentence(mt_rand(6, 12));

        return [
            'category'     => $this->faker->randomElement(self::CATEGORIES),
            'title'        => rtrim($title, '.') . '?',
            'slug'         => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'body'         => implode("\n\n", $this->faker->paragraphs(3)),
            'featured'     => $this->faker->boolean(20),
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function featured(): static
    {
        return $this->state(['featured' => true]);
    }
}
