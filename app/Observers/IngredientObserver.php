<?php

namespace App\Observers;

use App\Mail\IngredientLowStock;
use App\Models\Ingredient;
use Illuminate\Support\Facades\Mail;

class IngredientObserver
{
    /**
     * Handle the Ingredient "created" event.
     */
    public function created(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "updated" event.
     */
    public function updated(Ingredient $ingredient): void
    {
        // Check if the ingredient is 50% or less of the full stock, and not already notified
        if (!$ingredient->is_notified_of_low_stock && $ingredient->quantity <= ($ingredient->full_stock / 2)) {
            // Send an email to the admin
            $emailToNotify = config('app.low_stock_email');
            if ($emailToNotify) {
                Mail::to($emailToNotify)->queue(new IngredientLowStock($ingredient));
            }
            // Set the is_notified_of_low_stock flag to true
            $ingredient->updateQuietly([
                'is_notified_of_low_stock' => true,
            ]);
        }
        // Check if the ingredient is more than 50% of the full stock, and already notified
        if ($ingredient->is_notified_of_low_stock && $ingredient->quantity > ($ingredient->full_stock / 2)) {
            // Set the is_notified_of_low_stock flag to false
            $ingredient->updateQuietly([
                'is_notified_of_low_stock' => false,
            ]);
        }
    }

    /**
     * Handle the Ingredient "deleted" event.
     */
    public function deleted(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "restored" event.
     */
    public function restored(Ingredient $ingredient): void
    {
        //
    }

    /**
     * Handle the Ingredient "force deleted" event.
     */
    public function forceDeleted(Ingredient $ingredient): void
    {
        //
    }
}
