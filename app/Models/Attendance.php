<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'class_id',
        'schedule_id',
        'attendance_date',
        'status',
        'remarks',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('attendance_date', $date);
    }

    public function scopeByMonth($query, $month, $year)
    {
        return $query->whereMonth('attendance_date', $month)
                     ->whereYear('attendance_date', $year);
    }

    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }
}
