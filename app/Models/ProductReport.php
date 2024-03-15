<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_product_id',
        'machine_id',
        'date',
        'start_time',
        'end_time',
        'success_count',
        'error_count',
        'sort'
    ];

    protected $cast = [
        'date' => 'date',
        'time' => 'time'
    ];
}
