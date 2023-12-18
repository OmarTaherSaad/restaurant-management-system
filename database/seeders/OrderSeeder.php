<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
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

        //Add an auto-generated order
        // Generate order data
        $products = Product::all();
        $productsData = [];
        foreach ($products as $product) {
            $productsData[] = [
                'name' => $product->name,
                'quantity' => random_int(1, 4),
            ];
        }
        $orders[] = [
            'products' => $productsData,
        ];

        // Create the orders
        foreach ($orders as $orderData) {
            $order = Order::factory()->create();

            // Create the products
            foreach ($orderData['products'] as $productData) {
                $product = Product::where('name', $productData['name'])->first();
                $order->products()->attach($product->id, ['quantity' => $productData['quantity']]);
            }
        }
    }
}
