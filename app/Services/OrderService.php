<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class OrderService 
{
    public function createOrder(int $userId, Address $address, int $shippingCost,int $shippingDays, array $cart): int
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

    public function updateOrderStatus(int $orderId, string $status): ?bool
    {
        $order = Order::find($orderId);
        if (!$order) {
            return null;
        }

        $order->status = $status;
        return $order->save();
    }

    public function getListOrdersUser(User $user): Collection
    {
        $orders = Order::select('id', 'status', 'total', 'created_at')
            ->where('user_id', $user->id)
            ->orderBy('created_at', "DESC")
            ->get();

        return $orders;
    }

    /* 
    RETORNO:

    "order": {
        "id": 1,
        "status": "pending",
        "total": 199.99,
        "shippingCost": 7,
        "shippingDays": 3,
        "shippingZipcode": "12345-678",
        "shippingStreet": "Street Name",
        "shippingNumber": "123",
        "shippingCity": "City",
        "shippingState": "State",
        "shippingCountry": "Country",
        "shippingComplement": "Apt 1",
        "createdAt": "2024-07-24T18:49:43.000Z",
        "orderItems": [
            {
            "id": 1,
            "quantity": 2,
            "price": 99.99,
            "product": {
                "id": 1,
                "label": "Product Name",
                "price": 99.99,
                "image": "media/products/<filename>"
            }
            }
        ]
    } 
    */
    public function getOrderUser(User $user, int $orderId): Collection
    {
        return Order::with([
            'orderItems:id,order_id,product_id,quantity,price', 
            'orderItems.product:id,label,category_id',          
            'orderItems.product.category:id,name,slug'    
        ])
        ->where('user_id', $user->id)
        ->where('id', $orderId)
        ->get();
    }
    
}