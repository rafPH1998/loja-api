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
    ){ }

    /**
     * Handle the incoming request.
     */
    public function cartMound(CartMountRequest $request)
    {
        $data = $request->validated();

        return response()->json([
            'error' => null,
            'products' => Product::whereIn('id', $data['ids'])->get()
        ]);
    }

    public function cartShipping(CartShippingRequest $request)
    {
        $data = $request->validated();

        return response()->json([
            'error' => null,
            'zipcode' => $data['zipcode'],
            'cost' => 7,
            'days' => 3
        ]);
    }

    public function checkoutPaymentCart(CartFinishRequest $request)
    {
        $shippingCost = 7;
        $shippingDays = 3;
        $user = $request->user();

        try {
            $address = Address::findOrFail($request->addressId);

            $orderId = $this->orderService->createOrder(
                $user->id,
                $address,
                $shippingCost,
                $shippingDays,
                $request->cart
            );

            if (!$orderId) {
                return response()->json(['error' => 'Ocorreu um erro ao obter o pedido']);
            }

            $stripeUrl = $this->stripeService->createCheckoutSession(
                $user,
                $request->cart,
                $orderId,
                $address->id
            );

            if (!$stripeUrl) {
                return response()->json(['error' => 'Ocorreu um erro ao obter a URL do pedido']);
            }

            return response()->json([
                'error' => null,
                'url' => $stripeUrl,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar sessão de checkout: ' . $e->getMessage(),
                'url' => null,
            ], 500);
        }
    }

}
