<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $paidQuery = Order::query()->where('status', Order::STATUS_PAID);

        return response()->json([
            'error' => null,
            'stats' => [
                'products' => Product::count(),
                'users' => User::count(),
                'orders' => Order::count(),
                'pending_orders' => Order::where('status', Order::STATUS_PENDING)->count(),
                'paid_orders' => (clone $paidQuery)->count(),
                'revenue' => (float) (clone $paidQuery)->sum('total'),
            ],
            'recent_orders' => Order::with('user:id,name,email')
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
