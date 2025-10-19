<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Cria uma sessão de checkout no Stripe
     *
     * @param User $user
     * @param array $cart
     * @param int $orderId
     * @param int $addressId
     * @return string URL de checkout
     */
    public function createCheckoutSession(User $user, array $cart, int $orderId, int $addressId): string
    {
        $lineItems = collect($cart)
            ->map(function ($item) {
                $product = Product::find($item['productId']);
                if (!$product) return null;

                return [
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => $product->label,
                        ],
                        'unit_amount' => $product->price * 100,
                    ],
                    'quantity' => $item['quantity'],
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        $frontendUrl = $_ENV["FRONTEND_URL"] ?? "http://localhost:3000";

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            "success_url" => $frontendUrl . "/success?session_id={CHECKOUT_SESSION_ID}",
            "cancel_url" => $frontendUrl . "/cancel",
            'customer_email' => $user->email,
            'metadata' => [
                'order_id' => $orderId,
                'user_id' => $user->id,
                'address_id' => $addressId,
            ],
        ]);

        return $checkoutSession->url;
    }
}
