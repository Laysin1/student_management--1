<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Score;

class ScoreController extends Controller
{
    /**
     * Display scores for all students in the teacher's classes.
     */
    public function index()
    {
        $teacher = Auth::user()->teacher;

        // Get all classes for this teacher with students and their scores
        $classes = $teacher ? $teacher->classes()->with(['students.user', 'students.scores'])->get() : collect();

        // Get unique grades for filtering
        $grades = $teacher ? $teacher->classes->pluck('grade_level')->unique()->sort()->values() : collect();

        return view('teacher.score.index', compact('classes', 'grades'));
    }

    /**
     * Show the form for entering/editing scores for a specific class.
     */
    public function edit($classId)
    {
        $teacher = Auth::user()->teacher;

        // Verify teacher has access to this class
        $class = $teacher->classes()->with(['students.user', 'students.scores'])->findOrFail($classId);

        return view('teacher.score.edit', compact('class'));
    }

    /**
     * Update scores for students in a class.
     */
    public function update(Request $request, $classId)
    {
        $teacher = Auth::user()->teacher;

        // Verify teacher has access to this class
        $class = $teacher->classes()->findOrFail($classId);

        $validated = $request->validate([
            'scores' => 'required|array',
            'scores.*.student_id' => 'required|exists:students,id',
            'scores.*.subject_id' => 'required|exists:subjects,id',
            'scores.*.score' => 'required|numeric|min:0|max:100',
            'scores.*.term' => 'nullable|string|max:50',
        ]);

        foreach ($validated['scores'] as $scoreData) {
            Score::updateOrCreate(
                [
                    'student_id' => $scoreData['student_id'],
                    'subject_id' => $scoreData['subject_id'],
                    'term' => $scoreData['term'] ?? 'Term 1',
                ],
                [
                    'score' => $scoreData['score'],
                    'teacher_id' => $teacher->id,
                ]
            );
        }

        return redirect()->route('teacher.scores.index')->with('success', 'Scores updated successfully.');
    }

    /**
     * Show scores for a specific student.
     */

    // public function index()
    // {
    //     $teacher = Auth::user()->teacher;
    //     $classes = $teacher ? $teacher->classes()->with(['students.user', 'students.scores'])->get() : collect();
    //     $grades = $teacher ? $teacher->classes->pluck('grade_level')->unique()->sort()->values() : collect();
    //     return view('teacher.scores.index', compact('classes', 'grades'));
    // }

    // public function edit($classId)
    // {
    //     $teacher = Auth::user()->teacher;
    //     $class = $teacher->classes()->with(['students.user', 'students.scores'])->findOrFail($classId);
    //     return view('teacher.scores.edit', compact('class'));
    // }

    public function show($studentId)
    {
        $teacher = Auth::user()->teacher;
        $classIds = $teacher ? $teacher->classes->pluck('id') : collect();
        $student = \App\Models\Student::with(['user', 'class', 'scores.subject'])
            ->whereIn('class_id', $classIds)
            ->findOrFail($studentId);
        return view('teacher.scores.show', compact('student'));
    }
}
