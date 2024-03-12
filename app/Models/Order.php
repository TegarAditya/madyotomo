<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_number',
        'proof_number',
        'customer_id',
        'entry_date',
        'deadline_date',
        'paper_id',
        'paper_config',
        'machine_id',
        'finished_size',
        'material_size',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function deliveryOrder()
    {
        return $this->hasOne(DeliveryOrder::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderProduct::class);
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function spkProducts()
    {
        return $this->hasMany(SpkProduct::class);
    }

    public function spks()
    {
        return $this->hasMany(Spk::class);
    }
}
