<?php

namespace Database\Factories;

use App\Models\GalleryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryItem>
 */
class GalleryItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => 'Boarding',
            'image' => null,
            'image_alt' => fake()->sentence(),
            'caption' => null,
            'sort_order' => fake()->numberBetween(0, 100),
            'status' => GalleryItem::STATUS_DRAFT,
            'published_at' => null,
        ];
    }
}
