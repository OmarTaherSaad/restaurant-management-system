<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'stock_updated',
    ];


    #region Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    #endregion

    public function updateStock()
    {
        if ($this->stock_updated) {
            return;
        }
        // Update stock for each ingredient, based on the quantity of each product
        foreach ($this->products as $product) {
            foreach ($product->ingredients as $ingredient) {
                // Assuming that the quantity of the ingredient is in grams,
                // and order is validated so that stock will never be negative
                $ingredient->quantity -= $product->pivot->quantity * $ingredient->pivot->quantity;
                $ingredient->save();
            }
        }
        $this->stock_updated = true;
        $this->save();
    }
}
