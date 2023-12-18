<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateIngredientWithOrdersTest extends TestCase
{
    /**
     * Test updating ingredients when an order is stored
     */
    public function test_updating_ingredients_when_an_order_is_stored()
    {
        $this->seed();
        // Generate order data
        $products = Product::all();
        $productsData = [];
        $ingredients = [];
        foreach ($products as $product) {
            $productCount = random_int(1, 4);
            // Get the product's ingredients and add them to the ingredients array
            foreach ($product->ingredients as $ingredient) {
                if (!isset($ingredients[$ingredient->id])) {
                    $ingredients[$ingredient->id] = 0;
                }
                $ingredients[$ingredient->id] += $ingredient->pivot->quantity * $productCount;
            }
            $productsData[] = [
                'product_id' => $product->id,
                'quantity' => $productCount,
            ];
        }
        $data = [
            'products' => $productsData,
        ];

        // Send a POST request to /api/v1/orders
        $response = $this->postJson(route('orders.store'), $data);
        // Assert it was successful
        $response->assertStatus(200);

        // Assert the ingredients were updated
        foreach ($ingredients as $ingredientId => $quantity) {
            $ingredient = Ingredient::find($ingredientId);
            $this->assertEquals($ingredient->quantity, $ingredient->full_stock - $quantity);
        }
    }
}
