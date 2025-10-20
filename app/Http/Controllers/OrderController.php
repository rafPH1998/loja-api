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
    ){ }
    
    /**
     * Handle the incoming request.
     */
    public function getOrderBySessionId(Request $request): JsonResponse
    {
        $request->validate([
            'session_id' => ['required', 'string']
        ]);

        $orderId = $this->stripeService->getOrderIdFromSession($request->session_id);

        if(!$orderId) {
            return response()->json(['error' => "Ocorreu um erro"]);
        }

        return response()->json(['error' => null, 'order_id' => $orderId]);
    }

    public function getListOrders(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = $this->orderService->getListOrdersUser($user);

        return response()->json(['error' => null, 'orders' => $orders]);
    }

    public function getOrderUser(Request $request, int $orderId): JsonResponse
    {
        $user = $request->user();

        $order = $this->orderService->getOrderUser($user, $orderId);

        return response()->json(['error' => null, 'order' => $order]);
    }
    
}
