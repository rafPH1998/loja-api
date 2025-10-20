<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\CategoryMetaData;
use App\Models\MetaDataValue;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductMetaData;
use App\Models\Order;
use App\Models\OrderProduct;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            Address::factory(2)->create(['user_id' => $user->id]);
            Banner::factory(1)->create();
            Order::factory(3)->create(['user_id' => $user->id])->each(function ($order) {
                OrderProduct::factory(2)->create(['order_id' => $order->id]);
            });
        });

        Category::factory(5)->create()->each(function ($category) {
            CategoryMetaData::factory(2)->create(['category_id' => $category->id]);
        });

        Product::factory(20)->create()->each(function ($product) {
            ProductImage::factory(3)->create(['product_id' => $product->id]);
            ProductMetaData::factory(2)->create(['product_id' => $product->id]);
        });

        MetaDataValue::factory(10)->create();
    }
}
