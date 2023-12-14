<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        //Assuming that sum of ingredient quantities is less than the stock and order is valid
        /** @var Order $order */
        $order = Order::create();
        foreach ($data['products'] as $product) {
            $order->products()->attach($product['product_id'], [
                'quantity' => $product['quantity'],
            ]);
        }
        //Update stock
        $order->updateStock();

        return response()->json([
            'message' => 'Order placed successfully',
            'order' => $order,
        ]);
    }
}
