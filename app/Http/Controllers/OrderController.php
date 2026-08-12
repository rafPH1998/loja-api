<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        protected StripeService $stripeService,
        protected OrderService $orderService,
    ) {
    }

    public function getOrderBySessionId(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => ['required', 'string'],
        ]);

        $orderId = $this->stripeService->getOrderIdFromSession($request->session_id);

        if (!$orderId) {
            return response()->json(['error' => 'Pedido não encontrado para esta sessão.'], 404);
        }

        return response()->json(['error' => null, 'order_id' => $orderId]);
    }

    public function getListOrders(Request $request): JsonResponse
    {
        $orders = $this->orderService->getListOrdersUser($request->user());

        return response()->json(['error' => null, 'orders' => $orders]);
    }

    public function getOrderUser(Request $request, int $orderId): JsonResponse
    {
        $order = $this->orderService->getOrderUser($request->user(), $orderId);

        if (!$order) {
            return response()->json(['error' => 'Pedido não encontrado.'], 404);
        }

        return response()->json(['error' => null, 'order' => $order]);
    }
}
