<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'category', 'price', 'image', 'attribute_type',
    ];
    public function attributes()
    {
        return $this->hasMany(Product_variation::class);
    }
}
