<?php

namespace Database\Factories;

use App\Models\CategoryMetaData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MetaDataValue>
 */
class MetaDataValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'label' => fake()->word(),
            'category_meta_data_id' => CategoryMetaData::factory(),
        ];
    }
}
