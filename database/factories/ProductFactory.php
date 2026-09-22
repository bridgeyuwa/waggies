<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(1000, 50000),
            'currency' => 'NGN',
            'category' => 'Accessories',
            'image_alt' => fake()->sentence(3),
            'features' => [fake()->sentence()],
            'status' => Product::STATUS_PUBLISHED,
            'sort_order' => 0,
        ];
    }
}
