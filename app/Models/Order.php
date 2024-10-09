<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner_id',
        'user_name',
        'user_email',
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_city',
        'shipping_upzila',
        'shipping_address',
        'shipping_zip',
        'status',
        'order_note',
        'sub_total',
        'shipping_cost',
        'bill',
        'payment_method',
        'transaction_id',
        'account_no',
    ];
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
    public function orderItems()
    {
        return $this->hasMany(Order_item::class);
    }
}
