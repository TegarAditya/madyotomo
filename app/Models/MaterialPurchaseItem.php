<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialPurchaseItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_purchase_id',
        'material_id',
        'quantity',
        'price',
    ];

    public function getTotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function materialPurchase()
    {
        return $this->belongsTo(MaterialPurchase::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
