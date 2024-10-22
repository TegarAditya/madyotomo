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
        'name',
        'customer_id',
        'entry_date',
        'deadline_date',
        'paper_id',
        'paper_config',
        'machine_id',
        'finished_size',
        'material_size',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'deadline_date' => 'date',
    ];

    public function getStatusAttribute()
    {
        $status = 'Belum Selesai';

        if ($this->invoices()->exists()) {
            $status = 'Invoice Dibuat';
        } elseif ($this->deliveryOrders()->exists()) {
            $status = 'Surat Jalan Dibuat';
        } elseif ($this->spks()->exists()) {
            $status = 'SPK Dibuat';
        }

        return $status;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderProduct::class);
    }

    public function order_products()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderProducts()
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

    public function hasSpk()
    {
        return $this->spks()->exists();
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function hasInvoice()
    {
        return $this->invoices()->exists();
    }
}
