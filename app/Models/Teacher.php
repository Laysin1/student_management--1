<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'gender',
        'subject_id',
        'profile_photo_path',
    ];

    /**
     * Get the user that owns this teacher.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * Get the subject for this teacher.
     */
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    /**
     * Get the classes this teacher teaches.
     */
    public function classes()
    {
        return $this->hasMany(\App\Models\SchoolClass::class, 'teacher_id');
    }

    /**
     * Alias for classes relationship
     */
    public function class()
    {
        return $this->hasMany(\App\Models\SchoolClass::class, 'teacher_id');
    }

    /**
     * Get the schedules for this teacher.
     */
    public function schedules()
    {
        return $this->hasMany(\App\Models\Schedule::class);
    }

    /**
     * Get the scores created by this teacher.
     */
    public function scores()
    {
        return $this->hasMany(\App\Models\Score::class);
    }
}
