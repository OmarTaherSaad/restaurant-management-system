<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        Create orders with products, for example:
        An order may have several products:
            - 1 Burger
            - 2 Cheese Burger
        */

        $orders = [
            [
                'name' => 'Order 1',
                'description' => 'A delicious order',
                'products' => [
                    [
                        'name' => 'Burger',
                        'quantity' => 1,
                    ],
                    [
                        'name' => 'Cheese Burger',
                        'quantity' => 2,
                    ],
                ],
            ],
            [
                'name' => 'Order 2',
                'description' => 'A delicious order',
                'products' => [
                    [
                        'name' => 'Burger',
                        'quantity' => 1,
                    ],
                    [
                        'name' => 'Cheese Burger',
                        'quantity' => 2,
                    ],
                ],
            ],
        ];

        // Create the orders
        foreach ($orders as $order) {
            $orderModel = \App\Models\Order::factory()->create($order);

            // Create the products
            foreach ($order['products'] as $product) {
                $productModel = \App\Models\Product::where('name', $product['name'])->first();
                $orderModel->products()->attach($productModel->id, ['quantity' => $product['quantity']]);
            }
        }
    }
}
