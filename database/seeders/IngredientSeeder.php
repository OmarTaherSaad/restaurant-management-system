<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        - 20kg Beef
        - 5kg Cheese
        - 1kg Onion
        */
        $ingredients = [
            [
                'name' => 'Beef',
                'quantity' => 20000,
                'unit' => 'g',
                'full_stock' => 20000,
            ],
            [
                'name' => 'Cheese',
                'quantity' => 5000,
                'unit' => 'g',
                'full_stock' => 5000,
            ],
            [
                'name' => 'Onion',
                'quantity' => 1000,
                'unit' => 'g',
                'full_stock' => 1000
            ],
        ];
        // Create the ingredients
        foreach ($ingredients as $ingredient) {
            \App\Models\Ingredient::factory()->create($ingredient);
        }
    }
}
