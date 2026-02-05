<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'student_id',
        'age',
        'phone_number',
        'parent_number',
        'gender',
        'class_id',
        'profile_photo_path',
        'date_of_birth',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function class()
    {
        return $this->belongsTo(\App\Models\SchoolClass::class, 'class_id');
    }
    protected static function booted()
{
    static::deleting(function ($student) {
        if ($student->user) { $student->user->delete(); }
    });
}
public function scores()
{
    return $this->hasMany(\App\Models\Score::class);
}
}
