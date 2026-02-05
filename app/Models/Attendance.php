<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_id',
        'attendance_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    /**
     * Get the student that has this attendance record
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the class that this attendance record belongs to
     */
    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    /**
     * Get attendance records for a specific date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    /**
     * Get attendance records for a specific month
     */
    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('attendance_date', $month)
                     ->whereYear('attendance_date', $year);
    }

    /**
     * Get attendance records for a specific student and date range
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
