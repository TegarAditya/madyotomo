<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialPurchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_supplier_id',
        'proof_number',
        'purchase_date',
        'is_paid',
        'paid_off_date',
        'notes',
    ];

    public function materialSupplier()
    {
        return $this->belongsTo(MaterialSupplier::class);
    }

    public function items()
    {
        return $this->hasMany(MaterialPurchaseItem::class);
    }
}
