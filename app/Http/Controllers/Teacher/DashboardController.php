<?php


namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function teacherHome()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('dashboard')->with('error', 'Teacher profile not found');
        }

        // Get teacher's classes
        $classes = \App\Models\SchoolClass::where('teacher_id', $teacher->id)
            ->with(['students', 'students.user'])
            ->get();

        $myClasses = $classes->count();
        $myStudents = $classes->sum(function($class) {
            return $class->students->count();
        });
        $mySchedules = \App\Models\Schedule::where(function($query) use ($teacher, $classes) {
            $query->where('teacher_id', $teacher->id)
                  ->orWhereIn('class_id', $classes->pluck('id')->toArray());
        })->count();
        $myMessages = 0; // Replace with your logic

        return view('teacher.dashboard.index', compact(
            'classes',
            'myClasses',
            'myStudents',
            'mySchedules',
            'myMessages'
        ));
    }
}
