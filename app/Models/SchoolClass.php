<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
        'grade_level',
        'schedule_image',
    ];

    /**
     * Students in this class
     */
    public function students()
    {
        return $this->hasMany(
            \App\Models\Student::class,
            'class_id'
        );
    }

    /**
     * Teachers assigned to this class
     */
    public function teachers()
    {
        return $this->belongsToMany(
            \App\Models\Teacher::class,
            'class_teacher',
            'class_id',
            'teacher_id'
        );
    }

    /**
     * Class schedules
     */
    public function schedules()
    {
        return $this->hasMany(
            \App\Models\Schedule::class,
            'class_id'
        );
    }

    /**
     * Attendance records
     */
    public function attendances()
    {
        return $this->hasMany(
            \App\Models\Attendance::class,
            'class_id'
        );
    }

    /**
     * Scores for this class
     */
    public function scores()
    {
        return $this->hasMany(
            \App\Models\Score::class,
            'class_id'
        );
    }
}
