<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\Services\WebhookService;
use App\Http\Requests\OrderCreateRequest;
use App\Http\Requests\OrderUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{


    public function index()
    {
        $orders = Order::all();

        return response()->json([
            'status' => 'success',
            'message' => "Fetched all orders.",
            'data' => [
                'order_count' => count($orders),
                'orders' => $orders
            ]
        ], 200);
    }

    public function create(OrderCreateRequest $request, OrderService $orderService)
    {
        try {

            $order = $orderService->create_new_order($request);
            return response()->json([
                'status' => 'success',
                'message' => 'Order created succesfully.',
                'data' => $order
            ], 201);

        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => "Error: " . $error->getMessage(),
            ], 500);
        }
    }

    public function update($id, OrderUpdateRequest $request, OrderService $orderService, WebhookService $webhookService)
    {

        try {
            // DB update
            $order = $orderService->update_order($id, $request);

            // webhook post
            $webhook_status = $webhookService->update_order($order);

            return response()->json([
                'status'  => 'success',
                'message' => 'Order updated successfully.',
                'data'    => [
                    'order'        => $order,
                    'webhook_sent' => $webhook_status,
                ],
            ], 200);


        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order not found.',
            ], 404);
            
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
