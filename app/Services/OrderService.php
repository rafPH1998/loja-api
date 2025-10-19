<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;

class OrderService 
{

    public function createOrder(
        int $userId,
        Address $address,
        int $shippingCost,
        int $shippingDays,
        array $cart
    ): int
    {
        $orderItems = collect($cart)
            ->map(function ($item) {
                $product = Product::find($item['productId']);
                if (!$product) return null;

                return [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $product->price,
                ];
            })
            ->filter() 
            ->values()
            ->toArray();

        $subTotal = collect($orderItems)->sum(fn($item) => $item['price'] * $item['quantity']);

        $total = $subTotal + $shippingCost;

        $order = Order::create([
            'user_id'            => $userId,
            'total'              => $total,
            'shipping_cost'      => $shippingCost,
            'shipping_days'      => $shippingDays,
            'shipping_zipcode'   => $address->zipcode,
            'shipping_street'    => $address->street,
            'shipping_number'    => $address->number,
            'shipping_city'      => $address->city,
            'shipping_state'     => $address->state,
            'shipping_country'   => $address->country,
            'shipping_complement'=> $address->complement,
        ]);

        if (!empty($orderItems)) {
            $order->orderItems()->createMany($orderItems);
        }

        return $order->id;
    }
}