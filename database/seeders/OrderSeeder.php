<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customer = User::query()->where('email', 'cliente@loja.com')->first();

        if (!$customer) {
            $this->command?->warn('Cliente não encontrado. Rode o UserSeeder antes.');
            return;
        }

        if (Order::query()->where('user_id', $customer->id)->exists()) {
            return;
        }

        $php = Product::query()->where('label', 'Camisa PHP')->first();
        $laravel = Product::query()->where('label', 'Camisa Laravel')->first();

        $items = collect([$php, $laravel])->filter();
        if ($items->isEmpty()) {
            return;
        }

        $subtotal = $items->sum(fn (Product $product) => $product->price);
        $shipping = 7;

        $order = Order::query()->create([
            'user_id' => $customer->id,
            'status' => Order::STATUS_PAID,
            'total' => $subtotal + $shipping,
            'shipping_cost' => $shipping,
            'shipping_days' => 3,
            'shipping_zipcode' => '01310100',
            'shipping_street' => 'Avenida Paulista',
            'shipping_number' => '1000',
            'shipping_city' => 'São Paulo',
            'shipping_state' => 'SP',
            'shipping_country' => 'Brasil',
            'shipping_complement' => 'Apto 12',
        ]);

        foreach ($items as $product) {
            OrderProduct::query()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }
    }
}
