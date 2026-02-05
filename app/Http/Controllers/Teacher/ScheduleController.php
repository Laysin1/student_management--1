<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('dashboard.teacher')->with('error', 'Teacher profile not found');
        }

        // Get all classes assigned to this teacher
        $teacherClasses = SchoolClass::where('teacher_id', $teacher->id)
            ->pluck('id')
            ->toArray();

        // Get schedules that are either:
        // 1. Assigned directly to this teacher, OR
        // 2. For classes assigned to this teacher
        $schedules = Schedule::where(function($query) use ($teacher, $teacherClasses) {
            $query->where('teacher_id', $teacher->id)
                  ->orWhereIn('class_id', $teacherClasses);
        })
        ->with(['class.students'])
        ->orderBy('day_of_week')
        ->orderBy('start_time')
        ->get();

        return view('teacher.schedule.index', compact('schedules', 'teacher'));
    }
}
