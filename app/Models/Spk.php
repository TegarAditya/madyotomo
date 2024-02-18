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
        'note',
        'print_type',
        'spare',
    ];
}
