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
        $orderProducts = $this->orderProducts;
        $statuses = $orderProducts->pluck('status');

        switch (true) {
            case $this->invoices()->exists():
                return 'Invoice Dibuat';

            case $this->deliveryOrders()->exists():
                return 'Surat Jalan Dibuat';

            case $statuses->every(fn ($status) => $status === 'Dicetak'):
                return 'Cetak Semua';

            case $statuses->contains('Dicetak'):
                return 'Cetak Sebagian';

            case $this->spks()->exists():
                return 'SPK Dibuat';

            default:
                return 'Pending';
        }
    }

    public function getIsPrintedAttribute()
    {
        $statuses = $this->orderProducts->pluck('status');

        return $statuses->every(fn ($status) => $status === 'Dicetak');
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
