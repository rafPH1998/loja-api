<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartMountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['pong' => true]);
});

Route::post('/login', [AuthController::class, 'auth']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user-addresses', [UserController::class, 'addAddresses']);
    Route::get('/user-addresses', [UserController::class, 'getAddresses']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/get-user-auth', [AuthController::class, 'getUserAuth']);

    Route::resource('/banners', BannerController::class);
    Route::resource('/products', ProductController::class);
    Route::get('/products/{id}/related', [ProductController::class, 'getRelatedProducts']);
    Route::get('/category/{slug}/metadata', CategoryController::class);
    Route::get('/cart/mount', [CartMountController::class, 'cartMound']);
    Route::get('/cart/shipping', [CartMountController::class, 'cartShipping']);
    Route::post('/cart/finish', [CartMountController::class, 'finishCart']);
});
