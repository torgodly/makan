<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'status', 'user_id', 'payment_method', 'order_number', 'note'];


    //customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    //items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    //product
    public function product()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class, 'product_id', 'id', 'id', 'order_id');
    }


    //user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //total_price
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }
}
