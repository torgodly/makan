<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_date',
        'amount',
        'payment_method',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

}
