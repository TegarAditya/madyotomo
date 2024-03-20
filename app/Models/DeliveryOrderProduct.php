<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrderProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'delivery_order_id',
        'order_product_id',
        'quantity',
        'price',
        'total',
        'sort',
    ];

    public function deliveryOrder()
    {
        return $this->belongsTo(DeliveryOrder::class);
    }

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }
}
