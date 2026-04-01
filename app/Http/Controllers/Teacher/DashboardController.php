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

        // Get classes using pivot table relationship
        $classes = $teacher->classes()->with('students.user')->get();
        $myClasses = $classes->count();
        $myStudents = $classes->flatMap(fn($class) => $class->students)->unique('id')->count();
        $mySchedules = $teacher->schedules()->count();
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
