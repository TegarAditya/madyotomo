<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'document_number',
        'report_number',
        'entry_date',
        'deadline_date',
        'paper_config',
        'configuration',
        'note',
        'print_type',
        'spare',
    ];

    protected $casts = [
        'entry_date' => 'datetime',
        'deadline_date' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function spkProducts()
    {
        return $this->hasMany(SpkProduct::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function productReports()
    {
        return $this->hasMany(ProductReport::class);
    }
}
