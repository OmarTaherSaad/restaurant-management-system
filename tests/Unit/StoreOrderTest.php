<?php

namespace Tests\Unit;

use App\Models\Product;
use Tests\TestCase;

class StoreOrderTest extends TestCase
{
    /**
     * Test storing an order
     */
    public function test_storing_an_order()
    {
        // Seed the database
        $this->seed();
        // Generate order data
        $products = Product::all();
        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = [
                'product_id' => $product->id,
                'quantity' => random_int(1, 4),
            ];
        }
        $data = [
            'products' => $productsData,
        ];
        // Send a POST request to /api/v1/orders
        $response = $this->postJson(route('orders.store'), $data);
        // Assert it was successful
        $response->assertStatus(200);
        // Assert the order exists in the database
        $this->assertDatabaseHas('orders', [
            'id' => $response->json('order.id'),
        ]);
        // Assert the order has the correct products
        foreach ($productsData as $productData) {
            $this->assertDatabaseHas('order_product', [
                'order_id' => $response->json('order.id'),
                'product_id' => $productData['product_id'],
                'quantity' => $productData['quantity'],
            ]);
        }
    }
}
