<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'document_number',
        'entry_date',
        'due_date',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderProductInvoices()
    {
        return $this->hasMany(OrderProductInvoice::class);
    }
}
