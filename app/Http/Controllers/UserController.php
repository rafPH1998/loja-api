<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressStoreRequest;
use App\Models\Address;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
        return response()->json([
            'error' => null,
            'addresses' => $request->user()->addresses()->latest()->get(),
        ]);
    }

    public function deleteAddress(Request $request, Address $address)
    {
        if ($address->user_id !== $request->user()->id) {
            return response()->json([
                'error' => 'Endereço não encontrado.',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'error' => null,
            'message' => 'Endereço removido com sucesso.',
        ]);
    }
}
