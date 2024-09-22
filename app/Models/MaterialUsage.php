<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialUsage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'usage_date',
        'notes',
    ];

    public function items()
    {
        return $this->hasMany(MaterialUsageItem::class);
    }
}
