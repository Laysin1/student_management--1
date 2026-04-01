<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        if (!$student) {
            return redirect()->route('login.student');
        }

        $student->load(['class.students', 'class.teacher.subject', 'user']);

        return view('student.dashboard.index', compact('student'));
    }
}
