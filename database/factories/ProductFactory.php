<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
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
            'label' => fake()->words(3, true),
            'price' => fake()->randomFloat(2, 10, 1000),
            'description' => fake()->paragraph(),
            'liked' => false,
            'category_id' => Category::factory(),
            'views_count' => fake()->numberBetween(0, 1000),
            'sales_count' => fake()->numberBetween(0, 500),
        ];
    }
}
