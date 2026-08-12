<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@loja.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );

        $customer = User::query()->updateOrCreate(
            ['email' => 'cliente@loja.com'],
            [
                'name' => 'Cliente Loja',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ]
        );

        Address::query()->updateOrCreate(
            [
                'user_id' => $customer->id,
                'zipcode' => '01310100',
            ],
            [
                'street' => 'Avenida Paulista',
                'number' => '1000',
                'city' => 'São Paulo',
                'state' => 'SP',
                'country' => 'Brasil',
                'complement' => 'Apto 12',
            ]
        );

        Address::query()->updateOrCreate(
            [
                'user_id' => $admin->id,
                'zipcode' => '40020000',
            ],
            [
                'street' => 'Rua Chile',
                'number' => '10',
                'city' => 'Salvador',
                'state' => 'BA',
                'country' => 'Brasil',
                'complement' => null,
            ]
        );

        $camisas = Category::query()->updateOrCreate(
            ['slug' => 'camisas'],
            ['name' => 'Camisas']
        );

        $kits = Category::query()->updateOrCreate(
            ['slug' => 'kits'],
            ['name' => 'Kits']
        );

        $catalog = [
            [
                'label' => 'Camisa PHP',
                'price' => 49.90,
                'description' => 'Camisa confortável para quem vive de código e café.',
                'category_id' => $camisas->id,
                'stock' => 40,
                'views_count' => 320,
                'sales_count' => 48,
                'image' => '/assets/products/camiseta-php.png',
            ],
            [
                'label' => 'Camisa Laravel',
                'price' => 39.90,
                'description' => 'Homenagem à framework PHP mais elegante da web.',
                'category_id' => $camisas->id,
                'stock' => 35,
                'views_count' => 410,
                'sales_count' => 62,
                'image' => '/assets/products/camiseta-laravel-azul.png',
            ],
            [
                'label' => 'Camisa Node',
                'price' => 29.90,
                'description' => 'Para o time JavaScript que não para de criar APIs.',
                'category_id' => $camisas->id,
                'stock' => 50,
                'views_count' => 280,
                'sales_count' => 39,
                'image' => '/assets/products/camiseta-node.png',
            ],
            [
                'label' => 'Camisa React',
                'price' => 19.90,
                'description' => 'UI declarativa, estilo impecável.',
                'category_id' => $camisas->id,
                'stock' => 60,
                'views_count' => 190,
                'sales_count' => 21,
                'image' => '/assets/products/camiseta-react-azul.png',
            ],
            [
                'label' => 'Camisa Docker',
                'price' => 19.90,
                'description' => 'Containerize seu estilo com essa camisa.',
                'category_id' => $kits->id,
                'stock' => 25,
                'views_count' => 150,
                'sales_count' => 18,
                'image' => '/assets/products/camisa-docker.png',
            ],
        ];

        foreach ($catalog as $item) {
            $image = $item['image'];
            unset($item['image']);

            $product = Product::query()->updateOrCreate(
                ['label' => $item['label']],
                $item
            );

            ProductImage::query()->updateOrCreate(
                [
                    'product_id' => $product->id,
                    'url' => $image,
                ]
            );
        }

        $bannerImages = [
            '/assets/banners/banner-1.png',
            '/assets/banners/banner-2.png',
            '/assets/banners/banner-3.png',
            '/assets/banners/banner-4.png',
        ];

        foreach ($bannerImages as $index => $img) {
            Banner::query()->updateOrCreate(
                ['img' => $img],
                ['link' => $index % 2 === 0 ? '/categorias/camisas' : '/categorias/kits']
            );
        }

        if (Order::where('user_id', $customer->id)->count() === 0) {
            $order = Order::factory()->create([
                'user_id' => $customer->id,
                'status' => Order::STATUS_PAID,
                'total' => 96.80,
                'shipping_cost' => 7,
                'shipping_days' => 3,
            ]);

            $php = Product::where('label', 'Camisa PHP')->first();
            $laravel = Product::where('label', 'Camisa Laravel')->first();

            if ($php) {
                OrderProduct::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $php->id,
                    'quantity' => 1,
                    'price' => $php->price,
                ]);
            }

            if ($laravel) {
                OrderProduct::query()->create([
                    'order_id' => $order->id,
                    'product_id' => $laravel->id,
                    'quantity' => 1,
                    'price' => $laravel->price,
                ]);
            }
        }
    }
}
