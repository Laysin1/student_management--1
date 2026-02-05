<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = ['name', 'grade_level'];

    public function students()
    {
        return $this->hasMany(\App\Models\Student::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }

    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class, 'class_id');
    }
}
