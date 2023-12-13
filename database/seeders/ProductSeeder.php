<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        Create products with ingredients, for example:
        A Burger (Product) may have several ingredients:
            - 150g Beef
            - 30g Cheese
            - 20g Onion
        */

        $products = [
            [
                'name' => 'Burger',
                'description' => 'A delicious burger',
                'ingredients' => [
                    [
                        'name' => 'Beef',
                        'quantity' => 150,
                        'unit' => 'g',
                    ],
                    [
                        'name' => 'Cheese',
                        'quantity' => 30,
                        'unit' => 'g',
                    ],
                    [
                        'name' => 'Onion',
                        'quantity' => 20,
                        'unit' => 'g',
                    ],
                ],
            ],
            [
                'name' => 'Cheese Burger',
                'description' => 'A delicious cheese burger',
                'ingredients' => [
                    [
                        'name' => 'Beef',
                        'quantity' => 150,
                        'unit' => 'g',
                    ],
                    [
                        'name' => 'Cheese',
                        'quantity' => 80,
                        'unit' => 'g',
                    ],
                    [
                        'name' => 'Onion',
                        'quantity' => 20,
                        'unit' => 'g',
                    ],
                ],
            ],
            [
                'name' => 'Double Burger',
                'description' => 'A delicious double burger',
                'ingredients' => [
                    [
                        'name' => 'Beef',
                        'quantity' => 300,
                        'unit' => 'g',
                    ],
                    [
                        'name' => 'Cheese',
                        'quantity' => 50,
                        'unit' => 'g',
                    ],
                    [
                        'name' => 'Onion',
                        'quantity' => 30,
                        'unit' => 'g',
                    ],
                ],
            ],
        ];

        // Create the products
        foreach ($products as $product) {
            $ingredients = $product['ingredients'];
            unset($product['ingredients']);
            $product = \App\Models\Product::factory()->create($product);
            // Create the ingredients
            foreach ($ingredients as $productIngredient) {
                $ingredient = Ingredient::firstWhere('name', $productIngredient['name']);
                // Create the pivot
                $product->ingredients()->attach($ingredient->id, [
                    'quantity' => $productIngredient['quantity'],
                ]);
            }
        }
    }
}
