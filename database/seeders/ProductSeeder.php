<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $camisas = Category::query()->where('slug', 'camisas')->first();
        $kits = Category::query()->where('slug', 'kits')->first();

        if (!$camisas || !$kits) {
            $this->command?->warn('Categorias não encontradas. Rode o CategorySeeder antes.');
            return;
        }

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
    }
}
