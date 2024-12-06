<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'order_id',
        'description',
        'curriculum_id',
        'semester_id',
        'education_level_id',
        'education_class_id',
        'education_subject_id',
        'type_id',
        'quantity',
    ];

    public function getCodeAttribute()
    {
        $semester = $this->semester->code;
        $curriculum = $this->curriculum->code;
        $level = $this->educationLevel->code;
        $class = $this->educationClass->code;
        $subject = $this->educationSubject->code;
        $type = $this->type->code;

        return "C-{$level}{$curriculum}{$subject}{$class}{$semester}/{$type}";
    }

    public function getShortNameAttribute()
    {
        $class = $this->educationClass->name;
        $subject = $this->educationSubject->name;

        return "{$subject} {$class}";
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function curriculum()
    {
        return $this->belongsTo(Curriculum::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function educationClass()
    {
        return $this->belongsTo(EducationClass::class);
    }

    public function educationSubject()
    {
        return $this->belongsTo(EducationSubject::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    public function spkProducts()
    {
        return $this->hasMany(SpkProduct::class);
    }

    public function deliveryOrderProducts()
    {
        return $this->hasMany(DeliveryOrderProduct::class);
    }
}
