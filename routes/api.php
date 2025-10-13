<?php

use App\Http\Controllers\BannerController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['pong' => true]);
});

Route::resource('/banners', BannerController::class);
Route::resource('/products', ProductController::class);
Route::get('/products/{id}/related', [ProductController::class, 'getRelatedProducts']);

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */
