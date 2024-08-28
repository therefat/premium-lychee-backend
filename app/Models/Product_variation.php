<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product_variation extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id', 'attribute_quantity', 'attribute_price', 'attribute_type',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
