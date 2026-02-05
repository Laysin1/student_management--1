<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('dashboard.teacher')->with('error', 'Teacher profile not found');
        }

        // Get all classes - either assigned to this teacher or all classes as fallback
        $classes = \App\Models\SchoolClass::where('teacher_id', $teacher->id)
            ->orWhereNull('teacher_id')
            ->with(['students', 'students.user'])
            ->get();

        // If still no classes, get all classes
        if ($classes->isEmpty()) {
            $classes = \App\Models\SchoolClass::with(['students', 'students.user'])->get();
        }

        return view('teacher.classes.index', compact('classes'));
    }

    public function attend()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher ? $teacher->classes()->get() : collect();
        $selectedClassStudents = collect();

        if (request('class_id')) {
            $selectedClass = $teacher->classes()->find(request('class_id'));
            $selectedClassStudents = $selectedClass ? $selectedClass->students()->with('user')->get() : collect();
        }

        return view('teacher.classes.attend', compact('classes', 'selectedClassStudents'));
    }

    public function saveAttend(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date',
            'attendance' => 'array',
            'remarks' => 'array',
        ]);

        foreach ($validated['attendance'] as $studentId => $status) {
            \App\Models\Attendance::updateOrCreate(
                ['student_id' => $studentId, 'attendance_date' => $validated['attendance_date']],
                [
                    'class_id' => $validated['class_id'],
                    'status' => $status,
                    'remarks' => $validated['remarks'][$studentId] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.classes.attend')->with('success', 'Attendance saved successfully');
    }

    public function saveScores(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'month' => 'required|integer|min:1|max:12',
        ]);

        $currentYear = date('Y');

        // Handle first_semester scores
        foreach ($request->input('first_semester', []) as $studentId => $score) {
            if ($score) {
                \App\Models\Score::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $validated['class_id'],
                        'month' => $validated['month'],
                    ],
                    [
                        'first_semester' => $score,
                        'year' => $currentYear,
                    ]
                );
            }
        }

        // Handle second_semester scores
        foreach ($request->input('second_semester', []) as $studentId => $score) {
            if ($score) {
                \App\Models\Score::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $validated['class_id'],
                        'month' => $validated['month'],
                    ],
                    [
                        'second_semester' => $score,
                        'year' => $currentYear,
                    ]
                );
            }
        }

        // Handle final_score scores
        foreach ($request->input('final_score', []) as $studentId => $score) {
            if ($score) {
                \App\Models\Score::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'class_id' => $validated['class_id'],
                        'month' => $validated['month'],
                    ],
                    [
                        'final_score' => $score,
                        'grade' => $this->calculateGrade($score),
                        'year' => $currentYear,
                    ]
                );
            }
        }

        return redirect()->route('teacher.classes.scores', [
            'class_id' => $validated['class_id'],
            'month' => $validated['month'],
            'report_type' => $request->input('report_type'),
            'semester' => $request->input('semester'),
        ])->with('success', 'Scores saved successfully');
    }

    private function calculateGrade($score)
    {
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }    public function show($id)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.classes.index');
        }

        // Get the class with students
        $class = $teacher->classes()->with('students.user')->findOrFail($id);

        return view('teacher.classes.show', compact('class'));
    }

    public function create()
    {
        return view('teacher.classes.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('teacher.classes.index');
    }

    public function edit($class)
    {
        $teacher = Auth::user()->teacher;
        $class = $teacher->classes()->findOrFail($class);
        return view('teacher.classes.edit', compact('class'));
    }

    public function update(Request $request, $class)
    {
        return redirect()->route('teacher.classes.index');
    }

    public function destroy($class)
    {
        return redirect()->route('teacher.classes.index');
    }

    public function attendanceReport()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher ? $teacher->classes()->get() : collect();
        $attendanceRecords = collect();

        if (request('class_id')) {
            $query = \App\Models\Attendance::where('class_id', request('class_id'))
                ->with(['student.user']);

            // Filter by date
            if (request('attendance_date')) {
                $query->whereDate('attendance_date', request('attendance_date'));
            }

            // Filter by status
            if (request('status')) {
                $query->where('status', request('status'));
            }

            $attendanceRecords = $query->orderBy('attendance_date', 'desc')->paginate(50);
        }

        return view('teacher.classes.attendanceReport', compact('classes', 'attendanceRecords'));
    }

    public function scoresReport()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher ? $teacher->classes()->get() : collect();
        $scoreRecords = collect();

        if (request('class_id')) {
            $query = \App\Models\Score::where('class_id', request('class_id'))
                ->with(['student.user']);

            // Filter by report type and semester
            if (request('report_type') == 'semester') {
                if (request('semester') == '1') {
                    $query->whereNotNull('first_semester');
                } elseif (request('semester') == '2') {
                    $query->whereNotNull('second_semester');
                }
            } elseif (request('report_type') == 'final') {
                $query->whereNotNull('final_score');
            } elseif (request('report_type') == 'monthly') {
                $query->whereNotNull('final_score');
            }

            // Filter by month if provided
            if (request('month')) {
                $query->where('month', request('month'));
            }

            $scoreRecords = $query->orderBy('created_at', 'desc')->paginate(50);
        }

        return view('teacher.classes.scoresReport', compact('classes', 'scoreRecords'));
    }    public function scores()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher ? $teacher->classes()->get() : collect();
        $selectedClassStudents = collect();

        if (request('class_id')) {
            $selectedClass = $teacher->classes()->find(request('class_id'));
            $selectedClassStudents = $selectedClass ? $selectedClass->students()->with('user')->get() : collect();
        }

        return view('teacher.classes.scores', compact('classes', 'selectedClassStudents'));
    }
}
