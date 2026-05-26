<?php

namespace App\Http\Controllers\Parent;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
{
    $parent = DB::table('parent_users')
        ->where('user_id', Auth::id())
        ->first();

    abort_if(!$parent, 403, 'Parent profile not found');

    $studentIds = DB::table('parent_student')
        ->where('parent_user_id', $parent->id)
        ->pluck('student_id');

    $students = Student::whereIn('id', $studentIds)
        ->get();

    return view('parent.dashboard.index', compact('parent', 'students'));
}

    public function classes()
{
    $parent = DB::table('parent_users')
        ->where('user_id', Auth::id())
        ->first();

    abort_if(!$parent, 403, 'Parent profile not found');

    $studentIds = DB::table('parent_student')
        ->where('parent_user_id', $parent->id)
        ->pluck('student_id');

    $students = Student::whereIn('id', $studentIds)
        ->get();

    return view('parent.classes.index', compact('students'));
}

    public function grades($studentId)
    {
        $parent = DB::table('parent_users')
            ->where('user_id', Auth::id())
            ->first();

        abort_if(!$parent, 403, 'Parent profile not found');

        $hasStudent = DB::table('parent_student')
            ->where('parent_user_id', $parent->id)
            ->where('student_id', $studentId)
            ->exists();

        abort_if(!$hasStudent, 403, 'Unauthorized');

        $student = Student::findOrFail($studentId);

        return view('parent.grade_report.index', compact('student'));
    }

    public function attendance($studentId)
    {
        $parent = DB::table('parent_users')
            ->where('user_id', Auth::id())
            ->first();

        abort_if(!$parent, 403, 'Parent profile not found');

        $hasStudent = DB::table('parent_student')
            ->where('parent_user_id', $parent->id)
            ->where('student_id', $studentId)
            ->exists();

        abort_if(!$hasStudent, 403, 'Unauthorized');

        $student = Student::findOrFail($studentId);

        return view('parent.attendance.index', compact('student'));
    }

    public function schedule()
    {
        $parent = DB::table('parent_users')
            ->where('user_id', Auth::user()->id)
            ->first();

        if (!$parent) {
            abort(403, 'Parent profile not found');
        }

        return view('parent.schedule.index');
    }
}
