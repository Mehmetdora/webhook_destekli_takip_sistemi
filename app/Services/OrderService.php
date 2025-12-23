<?php


namespace App\Services;

use App\Models\Order;
use Exception;

class OrderService
{

    public function create_new_order($orderData)
    {
        $order = new Order();

        $order->order_no = $orderData->order_no;
        $order->customer_name = $orderData->customer_name;
        $order->total_price = $orderData->total_price;
        $order->status = $orderData->status;

        $order->save();
        return $order;
    }

    public function update_order($id, $orderData)
    {
        $order = Order::findOrFail($id);
        
        $order->status = $orderData->status;

        $order->save();
        return $order;
    }
}
