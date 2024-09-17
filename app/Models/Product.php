<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'description', 'category_id', 'price', 'image', 'attribute_type',
    ];

   public function categories()
   {
       return $this->belongsTo(Categories::class,'category_id');
   }
    public function attributes()
    {
        return $this->hasMany(ProductVariation::class,"product_id");
    }
    public function gallery()
    {
        return $this->hasMany(Product::class);
    }
}
