<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $table = 'scores';

    protected $fillable = [
        'student_id',
        'class_id',
        'subject',
        'first_semester',
        'second_semester',
        'final_score',
        'grade',
        'month',
        'year',
    ];

    protected $casts = [
        'first_semester' => 'integer',
        'second_semester' => 'integer',
        'final_score' => 'integer',
        'month' => 'integer',
        'year' => 'integer',
    ];

    /**
     * Get the student that has this score
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class that this score belongs to
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Get scores for a specific month
     */
    public function scopeByMonth($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }

    /**
     * Get scores for a specific student
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Get scores for a specific semester
     */
    public function scopeFirstSemester($query)
    {
        return $query->whereNotNull('first_semester');
    }

    public function scopeSecondSemester($query)
    {
        return $query->whereNotNull('second_semester');
    }

    /**
     * Calculate average score
     */
    public function getAverageAttribute()
    {
        if ($this->first_semester && $this->second_semester) {
            return round(($this->first_semester + $this->second_semester) / 2);
        }
        return null;
    }
}
