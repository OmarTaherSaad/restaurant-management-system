<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        //Validate all products' ingredients are available with required quantity in stock
        $ingredients = [];
        foreach ($data['products'] as $orderProduct) {
            $product = Product::find($orderProduct['product_id']);
            foreach ($product->ingredients as $ingredient) {
                if (!isset($ingredients[$ingredient->id])) {
                    $ingredients[$ingredient->id] = 0;
                }
                $ingredients[$ingredient->id] += $ingredient->pivot->quantity * $orderProduct['quantity'];
            }
        }
        foreach ($ingredients as $ingredientId => $requiredQuantity) {
            $ingredient = Ingredient::find($ingredientId);
            if ($ingredient->quantity < $requiredQuantity) {
                return response()->json([
                    'message' => 'Ingredient "' . $ingredient->name . '" is not available in stock',
                ], 422);
            }
        }

        //Sum of ingredient quantities is less than the stock and order is valid => create order
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
