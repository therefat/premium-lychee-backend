<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_item extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_id',
        'item_id',
        'name',
        'price',
        'image',
        'quantity',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function item()
    {
        return $this->belongsTo(Product::class, 'item_id'); // Assuming you have an Item model
    }
}
