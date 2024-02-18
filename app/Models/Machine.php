<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machine extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'code',
        'paper_config'
    ];

    public function products()
    {
        return $this->hasMany(Order::class);
    }
}
