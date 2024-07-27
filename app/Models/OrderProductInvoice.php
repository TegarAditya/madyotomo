<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProductInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_product_id',
        'invoice_id',
        'quantity',
    ];

    public function orderProduct()
    {
        return $this->belongsTo(OrderProduct::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
