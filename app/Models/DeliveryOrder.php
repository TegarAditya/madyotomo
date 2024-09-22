<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'document_number',
        'entry_date',
        'note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function deliveryOrderProducts()
    {
        return $this->hasMany(DeliveryOrderProduct::class);
    }
}
