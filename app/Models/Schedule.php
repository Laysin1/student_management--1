<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'class_id',
        'teacher_id',
        'type',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'subject',
        'photo_path',
        'title',
    ];

    public function class()
    {
        return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }
}
