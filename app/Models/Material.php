<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'unit',
    ];

    public function getStocksAttribute()
    {
        $purchases = $this->purchases->sum('pivot.quantity') ?? 0;
        $usages = $this->usages->sum('pivot.quantity') ?? 0;

        return $purchases - $usages;
    }

    public function purchases()
    {
        return $this->belongsToMany(MaterialPurchase::class, 'material_purchase_items', 'material_id', 'material_purchase_id')
            ->withPivot([
                'quantity',
                'price',
            ]);
    }

    public function usages()
    {
        return $this->belongsToMany(MaterialUsage::class, 'material_usage_items', 'material_id', 'material_usage_id')
            ->withPivot([
                'quantity',
                'machine_id',
            ]);
    }
}
