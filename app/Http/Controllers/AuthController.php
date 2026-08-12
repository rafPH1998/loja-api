<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthApiRequest;
use App\Http\Requests\RegisterApiRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function auth(AuthApiRequest $req)
    {
        $user = User::where('email', '=', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 422);
        }

        $token = $user->createToken($req->input('device_name', 'web'))->plainTextToken;

        return response()->json([
            'error' => null,
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function getUserAuth()
    {
        return response()->json([
            'error' => null,
            'user' => auth()->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function register(RegisterApiRequest $req)
    {
        $newUser = User::query()->create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
            'is_admin' => false,
        ]);

        $token = $newUser->createToken($req->input('device_name', 'web'))->plainTextToken;

        return response()->json([
            'error' => null,
            'token' => $token,
            'user' => $newUser,
        ], 201);
    }
}
