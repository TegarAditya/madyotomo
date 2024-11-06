<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'spk_id',
        'spk_order_product_id',
        'machine_id',
        'date',
        'start_time',
        'end_time',
        'success_count',
        'error_count',
        'status',
        'sort',
    ];

    protected $cast = [
        'date' => 'date',
        'start_time' => 'time',
        'end_time' => 'time',
        'status' => 'boolean',
    ];

    public function scopeWithDuration($query)
    {
        $query->selectRaw("*, TIMEDIFF(end_time, start_time) as duration");
    }

    public function getDurationAttribute($value)
    {
        return $value ?? \Carbon\Carbon::parse($this->start_time)->diff(\Carbon\Carbon::parse($this->end_time))->format('%H:%I:%S');
    }

    public function spk()
    {
        return $this->belongsTo(Spk::class);
    }

    public function spkProduct()
    {
        return $this->belongsTo(SpkProduct::class, 'spk_order_product_id', 'id');
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
