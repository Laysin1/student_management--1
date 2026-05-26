<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('dashboard.teacher')->with('error', 'Teacher profile not found');
        }

        $classes = $teacher->classes()->with(['students', 'students.user'])->get();

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
        $teacher = Auth::user()->teacher;

        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'attendance_date' => 'required|date',
            'schedule_id' => 'nullable|exists:schedules,id',
            'attendance' => 'array',
            'remarks' => 'array',
        ]);

        foreach ($validated['attendance'] as $studentId => $status) {
            \App\Models\Attendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'teacher_id' => $teacher->id,
                    'schedule_id' => $validated['schedule_id'] ?? null,
                    'attendance_date' => $validated['attendance_date'],
                ],
                [
                    'class_id' => $validated['class_id'],
                    'status' => $status,
                    'remarks' => $validated['remarks'][$studentId] ?? null,
                ]
            );
        }

        return redirect()->route('teacher.classes.attendanceReport', [
            'class_id' => $validated['class_id'],
            'attendance_date' => $validated['attendance_date'],
            'status' => '',
        ])->with('success', 'Attendance saved successfully.');
    }

    public function saveScores(Request $request)
    {
        try {
            $classId = $request->input('class_id');
            $month = $request->input('month');
            $reportType = $request->input('report_type', 'monthly');
            $semester = $request->input('semester');

            $scoreField = 'final_score';

            if ($reportType == 'semester' && $semester == '1') {
                $scoreField = 'first_semester';
                $month = null;
            } elseif ($reportType == 'semester' && $semester == '2') {
                $scoreField = 'second_semester';
                $month = null;
            }

            $scores = $request->input($scoreField, []);
            $savedCount = 0;
            $teacherId = Auth::user()->teacher->id ?? null;

            foreach ($scores as $studentId => $score) {
                if ($score !== null && $score !== '' && (float) $score > 0) {
                    $student = \App\Models\Student::with('class')->find($studentId);

                    if ($student) {
                        $gradeLevelRaw = $student->class->grade_level ?? null;
                        preg_match('/\d+/', (string) $gradeLevelRaw, $matches);
                        $gradeLevel = isset($matches[0]) ? (int) $matches[0] : null;

                        $existing = DB::table('scores')
                            ->where('student_id', $student->id)
                            ->where('teacher_id', $teacherId)
                            ->where('class_id', $classId)
                            ->where('month', $month)
                            ->first();

                        if ($existing) {
                            DB::table('scores')
                                ->where('id', $existing->id)
                                ->update([
                                    $scoreField => (float) $score,
                                    'grade' => $this->calculateGrade($score),
                                    'year' => 2026,
                                    'grade_level' => $gradeLevel,
                                    'updated_at' => now(),
                                ]);
                        } else {
                            DB::table('scores')->insert([
                                'student_id' => $student->id,
                                'teacher_id' => $teacherId,
                                'class_id' => $classId,
                                'month' => $month,
                                $scoreField => (float) $score,
                                'grade' => $this->calculateGrade($score),
                                'year' => 2026,
                                'grade_level' => $gradeLevel,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }

                        $savedCount++;
                    }
                }
            }

            $this->calculateAndSaveFinalScores();

            return redirect()->route('teacher.classes.scoresReport', [
                'class_id' => $classId,
                'report_type' => $reportType,
                'semester' => $semester,
                'month' => $month,
            ])->with('success', "✅ Scores saved and final scores calculated! ($savedCount records)");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', "❌ Error: " . $e->getMessage());
        }
    }

    private function calculateGrade($score)
    {
        $score = (float) $score;

        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';

        return 'F';
    }

    private function getGradeStatus($score)
    {
        $score = (float) $score;

        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Very Good';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Satisfactory';

        return 'Needs Improvement';
    }

    public function show($id)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('teacher.classes.index');
        }

        $class = $teacher->classes()
            ->with(['students.user', 'students.class'])
            ->findOrFail($id);

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
            ->where('teacher_id', $teacher->id)
            ->with(['student.user', 'teacher.subject', 'schedule'])
            ->orderBy('attendance_date', 'desc');

        if (request('attendance_date')) {
            $query->whereDate('attendance_date', request('attendance_date'));
        }

        if (request('status')) {
            $query->where('status', request('status'));
        }

        if (request('search')) {
            $search = request('search');

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $attendanceRecords = $query->paginate(20);
    }

    return view('teacher.classes.attendanceReport', compact('classes', 'attendanceRecords'));
}

    public function scoresReport()
    {
        $teacher = Auth::user()->teacher;
        $classes = $teacher ? $teacher->classes()->get() : collect();
        $scoreRecords = collect();

        if (request('class_id')) {
            $teacherId = $teacher->id;

            $query = \App\Models\Score::where('class_id', request('class_id'))
                ->where('teacher_id', $teacherId)
                ->with(['student.user']);

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

            if (request('month')) {
                $query->where('month', request('month'));
            }

            $scoreRecords = $query->orderBy('created_at', 'desc')->paginate(50);
        }

        return view('teacher.classes.scoresReport', compact('classes', 'scoreRecords'));
    }

    public function scores()
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

    private function calculateMonthlyAverage($studentId, $year, $gradeLevel, $month)
    {
        return DB::table('scores')
            ->where('student_id', $studentId)
            ->where('year', $year)
            ->where('grade_level', $gradeLevel)
            ->where('month', $month)
            ->whereNotNull('final_score')
            ->avg('final_score');
    }

    private function calculateAverageOfMonthlyAverages($studentId, $year, $gradeLevel, array $months)
    {
        $monthlyAverages = [];

        foreach ($months as $month) {
            $monthlyAverage = $this->calculateMonthlyAverage($studentId, $year, $gradeLevel, $month);

            if ($monthlyAverage !== null) {
                $monthlyAverages[] = (float) $monthlyAverage;
            }
        }

        if (count($monthlyAverages) === 0) {
            return null;
        }

        return array_sum($monthlyAverages) / count($monthlyAverages);
    }

    private function calculateAndSaveFinalScores()
    {
        try {
            $year = 2026;
            $students = \App\Models\Student::with('class')->get();

            foreach ($students as $student) {
                $gradeLevelRaw = $student->class->grade_level ?? null;
                preg_match('/\d+/', (string) $gradeLevelRaw, $matches);
                $gradeLevel = isset($matches[0]) ? (int) $matches[0] : null;

                if (!$gradeLevel) {
                    continue;
                }

                // Semester 1 monthly average: Dec → Mar
                $semester1MonthlyAverage = $this->calculateAverageOfMonthlyAverages(
                    $student->id,
                    $year,
                    $gradeLevel,
                    [12, 1, 2, 3]
                );

                // Semester 1 exam average: average of all subjects in S1
                $semester1ExamAverage = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('year', $year)
                    ->where('grade_level', $gradeLevel)
                    ->whereNotNull('first_semester')
                    ->avg('first_semester');

                // Semester 2 monthly average: May → Jul
                $semester2MonthlyAverage = $this->calculateAverageOfMonthlyAverages(
                    $student->id,
                    $year,
                    $gradeLevel,
                    [5, 6, 7]
                );

                // Semester 2 exam average: average of all subjects in S2
                $semester2ExamAverage = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('year', $year)
                    ->where('grade_level', $gradeLevel)
                    ->whereNotNull('second_semester')
                    ->avg('second_semester');

                $finalSemester1 = null;
                $finalSemester2 = null;
                $finalScore = null;

                if ($semester1MonthlyAverage !== null && $semester1ExamAverage !== null) {
                    $finalSemester1 = ($semester1MonthlyAverage + $semester1ExamAverage) / 2;
                }

                if ($semester2MonthlyAverage !== null && $semester2ExamAverage !== null) {
                    $finalSemester2 = ($semester2MonthlyAverage + $semester2ExamAverage) / 2;
                }

                if ($finalSemester1 !== null && $finalSemester2 !== null) {
                    $finalScore = ($finalSemester1 + $finalSemester2) / 2;
                }

                DB::table('scores')->updateOrInsert(
                    [
                        'student_id' => $student->id,
                        'year' => $year,
                        'grade_level' => $gradeLevel,
                        'month' => null,
                        'teacher_id' => null,
                    ],
                    [
                        'class_id' => $student->class->id ?? null,
                        'first_semester_final' => $finalSemester1 !== null ? round($finalSemester1, 2) : null,
                        'second_semester_final' => $finalSemester2 !== null ? round($finalSemester2, 2) : null,
                        'final_score' => $finalScore !== null ? round($finalScore, 2) : null,
                        'grade' => $finalScore !== null ? $this->calculateGrade($finalScore) : null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        } catch (\Exception $e) {
            file_put_contents(
                storage_path('logs/score-save.log'),
                "ERROR in calculateAndSaveFinalScores: " . $e->getMessage() . "\n",
                FILE_APPEND
            );
        }
    }
}
