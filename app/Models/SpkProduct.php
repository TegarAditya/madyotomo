<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpkProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_id',
        'quantity',
        'order_products',
    ];

    protected $casts = [
        'order_products' => 'array',
    ];

    public function spk()
    {
        return $this->belongsTo(Spk::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
