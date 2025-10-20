<?php

namespace Database\Factories;

use App\Models\CategoryMetaData;
use App\Models\MetaDataValue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductMetaData>
 */
class ProductMetaDataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'category_meta_data_id' => CategoryMetaData::factory(),
            'meta_data_value_id' => MetaDataValue::factory(),
        ];
    }
}
