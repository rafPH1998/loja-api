<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthApiRequest;
use App\Http\Requests\RegisterApiRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function auth(AuthApiRequest $req)
    {
        $user = User::where('email', '=', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json(['error' => 'Credenciais inválidas'], 422);
        }
        $user->tokens()->delete();
        $token = $user->createToken($req->device_name)->plainTextToken;
        return response()->json(['token' => $token]);
    }

    public function getUserAuth()
    {
        $user = auth()->user();
        return response()->json($user);
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function register(RegisterApiRequest $req)
    {
        $newUser = User::query()->create([
            'name' => $req->name,
            'email' => $req->email,
            'password' => Hash::make($req->password),
        ]);

        if ($newUser) {
            $token = $newUser->createToken($req->name)->plainTextToken;
            return response()->json(['token' => $token], 201);
        }

        return response()->json(['error' => 'Erro ao criar o usuário.']);
    }
}
