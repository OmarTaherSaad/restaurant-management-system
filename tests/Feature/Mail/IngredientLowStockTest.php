<?php

namespace Tests\Feature\Mail;

use App\Mail\IngredientLowStock;
use App\Models\Ingredient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class IngredientLowStockTest extends TestCase
{
    /**
     * Test sending an email when an ingredient is low on stock
     */
    public function test_sending_an_email_when_an_ingredient_is_low_on_stock()
    {
        Mail::fake();

        // Create an ingredient with low stock
        $ingredient = Ingredient::factory()->create([
            'quantity' => 10,
            'full_stock' => 10,
        ]);
        // Update the ingredient above 50% of the stock
        $ingredient->update([
            'quantity' => 6,
        ]);
        // Assert no email was sent
        Mail::assertNothingQueued();
        // Update the ingredient to trigger the event
        $ingredient->update([
            'quantity' => 5,
        ]);
        // Assert an email was sent to the admin
        Mail::assertQueued(IngredientLowStock::class);
        // Update the ingredient to trigger the event again
        $ingredient->update([
            'quantity' => 4,
        ]);
        // Assert no email was sent again
        Mail::assertQueuedCount(1);
    }
}
