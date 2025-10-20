<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Webhook;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Support\Facades\Log;

class WebHookController extends Controller
{
    public function __construct(
        protected OrderService $orderService,
    ){ }

    public function stripe(Request $request)
    {
        $webhookSecret = config('services.stripe.webhook_secret');
        $signature     = $request->header('Stripe-Signature');
        $payload       = $request->getContent();

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);

            if (!$event->type) {
                return response()->json(['error' => 'Nenhum evento disponível. Tente novamente em alguns instantes'], 500);
            }
    
            Log::info('Stripe webhook recebido', [
                'type' => $event->type,
                'id' => $event->id,
            ]);
    
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            switch ($event->type) {
                case 'checkout.session.completed':
                    if ($orderId) {
                        $this->orderService->updateOrderStatus($orderId, Order::STATUS_PAID);
                    }
                    break;
            
                case 'checkout.session.expired':
                    if ($orderId) {
                        $this->orderService->updateOrderStatus($orderId, Order::STATUS_EXPIRED);
                    }
                    break;

                case 'checkout.session.async_payment_failed':
                    if ($orderId) {
                        $this->orderService->updateOrderStatus($orderId, Order::STATUS_CANCELLED);
                    }
                    break;
            
                default:
                    Log::info('Evento Stripe não tratado: ' . $event->type);
                    break;
            }
            
            return response()->json(['status' => 'success'], 200);

        } catch (\UnexpectedValueException $e) {
            Log::error('Invalid payload: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Invalid signature: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }
    }
}