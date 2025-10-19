<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartMountRequest;
use App\Http\Requests\CartShippingRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CartMountController extends Controller
{
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
}
