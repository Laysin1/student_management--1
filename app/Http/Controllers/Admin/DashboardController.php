<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTeachers  = Teacher::count();
        $totalClasses   = SchoolClass::count();
        $totalStudents  = Student::count();
        $totalSchedules = Schedule::count();

        return view('dashboards.admin', compact(
            'totalTeachers',
            'totalClasses',
            'totalStudents',
            'totalSchedules'
        ));
    }
}
