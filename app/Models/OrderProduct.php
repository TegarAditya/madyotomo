<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'order_id',
        'order_product_id',
        'quantity',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function spkOrderProdutcs()
    {
        return $this->hasMany(SpkProduct::class);
    }

    /**
     * Check if the OrderProduct has related SpkProducts.
     * 
     * @package App\Models\SpkProduct
     *
     * @return bool Returns true if the OrderProduct has related SpkProducts, false otherwise.
     */
    public function hasSpkProducts()
    {
        $spkProductArray = [];
        $orderProduct = $this;

        foreach ($orderProduct->order->spks as $spk) {
            foreach ($spk->spkProducts as $spkProduct) {
                foreach ($spkProduct->order_products as $orderProduct) {
                    $spkProductArray[] = $orderProduct;
                }
            }
        }

        return in_array($this->id, $spkProductArray);
    }

    public function deliveryOrderProducts()
    {
        return $this->hasMany(DeliveryOrderProduct::class);
    }

    public function hasDeliveryOrderProducts()
    {
        return $this->deliveryOrderProducts()->exists();
    }

    public function orderProductInvoices()
    {
        return $this->hasMany(OrderProductInvoice::class);
    }
}
