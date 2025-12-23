<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookService
{

    public function update_order(Order $order)
    {

        try {

            $url = env('WEBHOOK_URL');
            if (!$url) {
                Log::error('WEBHOOK_URL is not set in .env', [
                    'Order Id' => $order->id,
                    'Order No' => $order->order_no
                ]);
                return false;
            }

            $data = [
                'order_no' => $order->order_no,
                'customer_name' => $order->customer_name,
                'status' => $order->status,
                'total_price' => $order->total_price,
            ];

            $response = Http::timeout(10)->post($url, $data);

            // 500-599
            if ($response->serverError()) {
                Log::error('HTTP request failed - server error', [
                    'Order Id' => $order->id,
                    'Order No' => $order->order_no,
                    'url' => $url,
                    'method' => 'POST',
                    'status' => $response->status(),
                    'response_headers' => $response->headers(),
                    'response_body' => $response->body(),
                    'request_payload' => $data,
                ]);
                return false;
            }

            // 400-499
            if ($response->clientError()) {
                Log::error('HTTP request failed - client error', [
                    'Order Id' => $order->id,
                    'Order No' => $order->order_no,
                    'url' => $url,
                    'method' => 'POST',
                    'status' => $response->status(),
                    'response_headers' => $response->headers(),
                    'response_body' => $response->body(),
                    'request_payload' => $data,
                ]);
                return false;
            }

            return true;
        } catch (\Exception $error) {
            Log::error('Webhook Service Error', ['error' => $error->getMessage()]);
            return false;
        }
    }
}
