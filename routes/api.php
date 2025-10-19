<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['pong' => true]);
});

Route::resource('/banners', BannerController::class);
Route::resource('/products', ProductController::class);
Route::get('/products/{id}/related', [ProductController::class, 'getRelatedProducts']);
Route::get('/category/{slug}/metadata', CategoryController::class);

Route::post('/auth', [AuthController::class, 'auth']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/get-user-auth', [AuthController::class, 'getUserAuth'])->middleware('auth:sanctum');
