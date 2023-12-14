<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'full_stock',
        'is_notified_of_low_stock',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_ingredient')
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
