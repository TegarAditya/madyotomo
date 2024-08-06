<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_id',
        'spk_product_id',
        'spk_order_product_id',
        'machine_id',
        'date',
        'start_time',
        'end_time',
        'success_count',
        'error_count',
        'sort',
    ];

    protected $cast = [
        'date' => 'date',
        'time' => 'time',
    ];

    public function spk()
    {
        return $this->belongsTo(Spk::class);
    }

    public function spkProduct()
    {
        return $this->belongsTo(SpkProduct::class);
    }
}
