<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialUsageItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'material_usage_id',
        'material_id',
        'machine_id',
        'quantity',
    ];

    public function materialUsage()
    {
        return $this->belongsTo(MaterialUsage::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }
}
