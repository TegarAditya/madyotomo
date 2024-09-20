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

    private function getStocksAttribute()
    {
        $purchases = $this->purchases->sum('quantity');
        $usages = $this->usages->sum('quantity');

        return $purchases - $usages;
    }

    public function purchases()
    {
        return $this->belongsToMany(MaterialPurchase::class, 'material_purchase_items')
            ->using(MaterialPurchaseItem::class)
            ->withPivot([
                'quantity',
                'price',
            ]);
    }

    public function usages()
    {
        return $this->belongsToMany(MaterialUsage::class, 'material_usage_items')
            ->using(MaterialUsageItem::class)
            ->withPivot([
                'quantity',
                'machine_id',
            ]);
    }
}
