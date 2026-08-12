<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(
        User $user,
        array $cart,
        int $orderId,
        int $addressId,
        float $shippingCost = 0
    ): string {
        $lineItems = collect($cart)
            ->map(function ($item) {
                $product = Product::find($item['productId']);
                if (!$product) {
                    return null;
                }

                return [
                    'price_data' => [
                        'currency' => 'brl',
                        'product_data' => [
                            'name' => $product->label,
                        ],
                        'unit_amount' => (int) round($product->price * 100),
                    ],
                    'quantity' => (int) $item['quantity'],
                ];
            })
            ->filter()
            ->values()
            ->toArray();

        if ($shippingCost > 0) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => 'Frete',
                    ],
                    'unit_amount' => (int) round($shippingCost * 100),
                ],
                'quantity' => 1,
            ];
        }

        $frontendUrl = rtrim(config('app.frontend_url', 'http://localhost:5173'), '/');

        $checkoutSession = StripeSession::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $lineItems,
            'success_url' => $frontendUrl . '/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $frontendUrl . '/cancel',
            'customer_email' => $user->email,
            'metadata' => [
                'order_id' => (string) $orderId,
                'user_id' => (string) $user->id,
                'address_id' => (string) $addressId,
            ],
        ]);

        return $checkoutSession->url;
    }

    public function getOrderIdFromSession(string $sessionId)
    {
        try {
            $session = StripeSession::retrieve($sessionId);

            if ($session->payment_status !== 'paid') {
                return null;
            }

            return $session->metadata->order_id ?? null;
        } catch (\Exception $e) {
            Log::error('Erro ao buscar sessão Stripe: ' . $e->getMessage());
            return null;
        }
    }
}
