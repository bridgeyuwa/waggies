<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    private const BLOG_CATEGORIES = [
        'dog-care', 'cat-care', 'grooming-tips', 'nutrition', 'training', 'health-vet', 'relocation', 'news',
    ];

    private const GUIDE_CATEGORIES = [
        'new-pet-owner', 'dog-guides', 'cat-guides', 'nutrition', 'training', 'health', 'relocation', 'grooming',
    ];

    public function definition(): array
    {
        $type  = $this->faker->randomElement(['blog', 'guide']);
        $cats  = $type === 'blog' ? self::BLOG_CATEGORIES : self::GUIDE_CATEGORIES;
        $title = $this->faker->sentence(mt_rand(5, 10));

        return [
            'type'         => $type,
            'category'     => $this->faker->randomElement($cats),
            'title'        => rtrim($title, '.'),
            'slug'         => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'excerpt'      => $this->faker->paragraph(2),
            'body'         => implode("\n\n", $this->faker->paragraphs(6)),
            'image'        => null,
            'author'       => 'The Waggies Team',
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function blog(): static
    {
        return $this->state(['type' => 'blog', 'category' => $this->faker->randomElement(self::BLOG_CATEGORIES)]);
    }

    public function guide(): static
    {
        return $this->state(['type' => 'guide', 'category' => $this->faker->randomElement(self::GUIDE_CATEGORIES)]);
    }

    public function draft(): static
    {
        return $this->state(['published_at' => null]);
    }
}
