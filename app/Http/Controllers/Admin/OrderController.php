<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function __construct(protected OrderService $orderService)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $status = $request->string('status')->toString();
        $search = $request->string('search')->toString();

        $orders = Order::query()
            ->with(['user:id,name,email'])
            ->withCount('orderItems')
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($search, function ($query, $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('id', $search)
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->get();

        return response()->json([
            'error' => null,
            'orders' => $orders,
        ]);
    }

    public function show(Order $order): JsonResponse
    {
        $order->load([
            'user:id,name,email',
            'orderItems.product.images',
            'orderItems.product.category:id,name,slug',
        ]);

        return response()->json([
            'error' => null,
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, Order $order): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in([
                Order::STATUS_PENDING,
                Order::STATUS_PAID,
                Order::STATUS_EXPIRED,
                Order::STATUS_CANCELLED,
            ])],
        ]);

        $this->orderService->updateOrderStatus($order->id, $data['status']);

        return response()->json([
            'error' => null,
            'order' => $order->fresh(['user:id,name,email', 'orderItems.product.images']),
        ]);
    }
}
