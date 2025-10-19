<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartFinishRequest;
use App\Http\Requests\CartMountRequest;
use App\Http\Requests\CartShippingRequest;
use App\Models\Address;
use App\Models\Product;
use App\Services\OrderService;

class CartMountController extends Controller
{
    public function __construct(protected OrderService $orderService)
    { }

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

    public function finishCart(CartFinishRequest $request)
    {
        $shippingCost = 7;
        $shippingDays = 3;
        $user = $request->user();

        try {
            $address = Address::query()->where('id', '=', $request->addressId)->first();
            $this->orderService->createOrder($user->id, $address, $shippingCost, $shippingDays, $request->cart);
            $url = "";

            
            return response()->json(["error" => null, "url" => $url]);
    
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar sessão de checkout: ' . $e->getMessage(),
                'url' => null,
            ], 500);
        }
    }
}
