<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'subject_id',
        'score',
        'total_score',
        'grade',
        'grade_status',
        'date',
    ];

    protected $casts = [
        'date' => 'datetime',
        'score' => 'float',
        'total_score' => 'float',
    ];

    /**
     * Get the student associated with the grade
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject associated with the grade
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            Log::info('Creating grade:', $model->toArray());
        });

        static::created(function ($model) {
            Log::info('Grade created:', $model->toArray());
        });
    }
}
