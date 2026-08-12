<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(int $userId, Address $address, int $shippingCost, int $shippingDays, array $cart): int
    {
        $orderItems = collect($cart)
            ->map(function ($item) {
                $product = Product::find($item['productId']);
                if (!$product) {
                    return null;
                }

                return [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        $subTotal = collect($orderItems)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $total = $subTotal + $shippingCost;

        $order = Order::create([
            'user_id' => $userId,
            'total' => $total,
            'shipping_cost' => $shippingCost,
            'shipping_days' => $shippingDays,
            'shipping_zipcode' => $address->zipcode,
            'shipping_street' => $address->street,
            'shipping_number' => $address->number,
            'shipping_city' => $address->city,
            'shipping_state' => $address->state,
            'shipping_country' => $address->country,
            'shipping_complement' => $address->complement,
        ]);

        if (!empty($orderItems)) {
            $order->orderItems()->createMany($orderItems);
        }

        return $order->id;
    }

    public function updateOrderStatus(int $orderId, string $status): ?bool
    {
        $order = Order::with('orderItems')->find($orderId);
        if (!$order) {
            return null;
        }

        $wasPaid = $order->status === Order::STATUS_PAID;
        $order->status = $status;
        $saved = $order->save();

        if ($saved && $status === Order::STATUS_PAID && !$wasPaid) {
            $this->registerSale($order);
        }

        return $saved;
    }

    public function getListOrdersUser(User $user): Collection
    {
        return Order::select('id', 'status', 'total', 'shipping_cost', 'shipping_days', 'created_at')
            ->withCount('orderItems')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function getOrderUser(User $user, int $orderId): ?Order
    {
        return Order::with([
            'orderItems.product.images',
            'orderItems.product.category:id,name,slug',
        ])
            ->where('user_id', $user->id)
            ->where('id', $orderId)
            ->first();
    }

    private function registerSale(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if (!$product) {
                    continue;
                }

                $product->increment('sales_count', $item->quantity);

                if ($product->stock > 0) {
                    $product->decrement('stock', min($product->stock, $item->quantity));
                }
            }
        });
    }
}
