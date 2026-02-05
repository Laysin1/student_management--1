<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher ? $teacher->classes()->with('students.user')->get() : collect();
        $students = collect();
        if ($classes->count()) {
            $students = $classes->flatMap(fn($class) => $class->students)->unique('id');
        }
        return view('teacher.students.index', compact('students', 'classes'));
    }

    public function show($id)
    {
        $student = Student::with('user', 'class')->findOrFail($id);
        return view('teacher.students.show', compact('student'));
    }

    public function create()
    {
        return view('teacher.students.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('teacher.students.index');
    }

    public function edit($id)
    {
        $student = Student::findOrFail($id);
        return view('teacher.students.edit', compact('student'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('teacher.students.index');
    }

    public function destroy($id)
    {
        return redirect()->route('teacher.students.index');
    }
}
