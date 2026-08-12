<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartFinishRequest;
use App\Http\Requests\CartMountRequest;
use App\Http\Requests\CartShippingRequest;
use App\Models\Address;
use App\Models\Product;
use App\Services\OrderService;
use App\Services\StripeService;

class CartMountController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
        protected StripeService $stripeService,
    ) {
    }

    public function cartMound(CartMountRequest $request)
    {
        $ids = $request->validated('ids');

        return response()->json([
            'error' => null,
            'products' => Product::with(['images', 'category'])->whereIn('id', $ids)->get(),
        ]);
    }

    public function cartShipping(CartShippingRequest $request)
    {
        $data = $request->validated();
        $digits = preg_replace('/\D/', '', $data['zipcode']) ?? '';

        $cost = strlen($digits) >= 8 ? 7 : 12;
        $days = strlen($digits) >= 8 ? 3 : 5;

        return response()->json([
            'error' => null,
            'zipcode' => $data['zipcode'],
            'cost' => $cost,
            'days' => $days,
        ]);
    }

    public function checkoutPaymentCart(CartFinishRequest $request)
    {
        $shippingCost = 7;
        $shippingDays = 3;
        $user = $request->user();

        try {
            $address = Address::where('id', $request->addressId)
                ->where('user_id', $user->id)
                ->firstOrFail();

            $orderId = $this->orderService->createOrder(
                $user->id,
                $address,
                $shippingCost,
                $shippingDays,
                $request->cart
            );

            if (!$orderId) {
                return response()->json(['error' => 'Ocorreu um erro ao obter o pedido'], 500);
            }

            $stripeUrl = $this->stripeService->createCheckoutSession(
                $user,
                $request->cart,
                $orderId,
                $address->id,
                $shippingCost
            );

            if (!$stripeUrl) {
                return response()->json(['error' => 'Ocorreu um erro ao obter a URL do pedido'], 500);
            }

            return response()->json([
                'error' => null,
                'url' => $stripeUrl,
                'order_id' => $orderId,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar sessão de checkout: ' . $e->getMessage(),
                'url' => null,
            ], 500);
        }
    }
}
