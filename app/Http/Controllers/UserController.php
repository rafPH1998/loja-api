<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressStoreRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function addAddresses(AddressStoreRequest $request)
    {
        $user = $request->user();

        $address = $user->addresses()->create($request->validated());

        return response()->json([
            'error' => null,
            'message' => 'Endereço cadastrado com sucesso!',
            'address' => $address,
        ], 201);
    }

    public function getAddresses(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'error' => null,
            'addresses' => $user->addresses
        ]);
    }
}
