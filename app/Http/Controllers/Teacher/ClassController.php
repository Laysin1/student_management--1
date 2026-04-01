<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClassController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            return redirect()->route('dashboard.teacher')->with('error', 'Teacher profile not found');
        }

        // Get only classes assigned to this teacher via pivot table
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
        try {
            // Log everything
            file_put_contents(storage_path('logs/score-save.log'), "\n=== SAVE SCORES CALLED ===\n" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
            file_put_contents(storage_path('logs/score-save.log'), "Request data: " . json_encode($request->all()) . "\n", FILE_APPEND);

            $classId = $request->input('class_id');
            $month = $request->input('month');
            $reportType = $request->input('report_type', 'monthly');
            $semester = $request->input('semester');

            file_put_contents(storage_path('logs/score-save.log'), "Class: $classId, Type: $reportType, Month: $month, Semester: $semester\n", FILE_APPEND);

            // Determine which score field to save
            $scoreField = 'final_score';
            $month = $request->input('month');

            if ($reportType == 'semester' && $semester == '1') {
                $scoreField = 'first_semester';
                $month = null; // Set month to null for semester scores
            } elseif ($reportType == 'semester' && $semester == '2') {
                $scoreField = 'second_semester';
                $month = null; // Set month to null for semester scores
            }
            // For monthly, keep the month value from request

            file_put_contents(storage_path('logs/score-save.log'), "Score field: $scoreField\n", FILE_APPEND);

            // Get the scores from request
            $scores = $request->input($scoreField, []);

            file_put_contents(storage_path('logs/score-save.log'), "Scores received: " . json_encode($scores) . "\n", FILE_APPEND);

            $savedCount = 0;

            // Save scores to grades table
            foreach ($scores as $studentId => $score) {
                if ($score !== null && $score !== '' && (float)$score > 0) {
                    // Always set year to 2026
                    if ($month) {
                        $date = Carbon::createFromDate(2026, (int)$month, 1);
                    } else {
                        $date = Carbon::createFromDate(2026, 1, 1);
                    }

                    file_put_contents(storage_path('logs/score-save.log'), "Processing student $studentId with score $score\n", FILE_APPEND);

                    // Find the Student
                    $student = \App\Models\Student::find($studentId);

                    if ($student) {
                        file_put_contents(storage_path('logs/score-save.log'), "  Found: ID={$student->id}, StudentID={$student->student_id}\n", FILE_APPEND);

                        // Save to scores table with proper field and teacher_id
                        DB::table('scores')->updateOrInsert(
                            [
                                'student_id' => $student->id,
                                'class_id' => $classId,
                                'month' => $month,
                            ],
                            [
                                $scoreField => (float)$score,
                                'grade' => $this->calculateGrade($score),
                                'year' => 2026,
                                'teacher_id' => \Illuminate\Support\Facades\Auth::user()->teacher->id ?? null,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        $savedCount++;
                        file_put_contents(storage_path('logs/score-save.log'), "  ✓ Saved\n", FILE_APPEND);
                    } else {
                        file_put_contents(storage_path('logs/score-save.log'), "  ✗ Student $studentId not found\n", FILE_APPEND);
                    }
                }
            }

            file_put_contents(storage_path('logs/score-save.log'), "Total saved: $savedCount\n", FILE_APPEND);

            // Automatically calculate final scores after saving
            file_put_contents(storage_path('logs/score-save.log'), "Calculating final scores...\n", FILE_APPEND);
            $this->calculateAndSaveFinalScores();

            file_put_contents(storage_path('logs/score-save.log'), "Done!\n\n", FILE_APPEND);

            return redirect()->back()->with('success', "✅ Scores saved and final scores calculated! ($savedCount records)");
        } catch (\Exception $e) {
            file_put_contents(storage_path('logs/score-save.log'), "ERROR: " . $e->getMessage() . " " . $e->getFile() . ":" . $e->getLine() . "\n", FILE_APPEND);
            return redirect()->back()->with('error', "❌ Error: " . $e->getMessage());
        }
    }

    private function calculateGrade($score)
    {
        $score = (float)$score;
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    }

    private function getGradeStatus($score)
    {
        $score = (float)$score;
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

    private function calculateAndSaveFinalScores()
    {
        try {
            file_put_contents(storage_path('logs/score-save.log'), "  Starting final score calculations...\n", FILE_APPEND);

            $year = 2026;
            $students = \App\Models\Student::all();
            $updatedCount = 0;

            foreach ($students as $student) {
                file_put_contents(storage_path('logs/score-save.log'), "    Processing student {$student->id}\n", FILE_APPEND);

                // Get monthly scores for First Semester (Dec, Jan, Feb, Mar)
                $firstSemesterMonths = [12, 1, 2, 3];
                $firstSemesterMonthlyScores = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('year', $year)
                    ->whereIn('month', $firstSemesterMonths)
                    ->whereNotNull('month')
                    ->pluck('final_score')
                    ->filter(function($score) { return $score > 0; })
                    ->toArray();

                // Get First Semester score
                $firstSemesterScore = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('year', $year)
                    ->where(function($q) {
                        $q->whereNull('month')->orWhere('month', 0);
                    })
                    ->value('first_semester');

                file_put_contents(storage_path('logs/score-save.log'), "      First Sem Monthly: " . json_encode($firstSemesterMonthlyScores) . ", Score: $firstSemesterScore\n", FILE_APPEND);

                // Calculate First Semester Final
                $firstSemesterFinal = null;
                if (!empty($firstSemesterMonthlyScores) && $firstSemesterScore > 0) {
                    $avgMonthly = array_sum($firstSemesterMonthlyScores) / count($firstSemesterMonthlyScores);
                    $firstSemesterFinal = ($avgMonthly + $firstSemesterScore) / 2;
                    file_put_contents(storage_path('logs/score-save.log'), "      First Sem Final: $firstSemesterFinal\n", FILE_APPEND);
                }

                // Get monthly scores for Second Semester (Apr, May, Jun, Jul)
                $secondSemesterMonths = [4, 5, 6, 7];
                $secondSemesterMonthlyScores = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('year', $year)
                    ->whereIn('month', $secondSemesterMonths)
                    ->whereNotNull('month')
                    ->pluck('final_score')
                    ->filter(function($score) { return $score > 0; })
                    ->toArray();

                // Get Second Semester score
                $secondSemesterScore = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('year', $year)
                    ->where(function($q) {
                        $q->whereNull('month')->orWhere('month', 0);
                    })
                    ->value('second_semester');

                file_put_contents(storage_path('logs/score-save.log'), "      Second Sem Monthly: " . json_encode($secondSemesterMonthlyScores) . ", Score: $secondSemesterScore\n", FILE_APPEND);

                // Calculate Second Semester Final
                $secondSemesterFinal = null;
                if (!empty($secondSemesterMonthlyScores) && $secondSemesterScore > 0) {
                    $avgMonthly = array_sum($secondSemesterMonthlyScores) / count($secondSemesterMonthlyScores);
                    $secondSemesterFinal = ($avgMonthly + $secondSemesterScore) / 2;
                    file_put_contents(storage_path('logs/score-save.log'), "      Second Sem Final: $secondSemesterFinal\n", FILE_APPEND);
                }

                // Calculate Overall Final Score
                if ($firstSemesterFinal !== null && $secondSemesterFinal !== null) {
                    $finalScore = ($firstSemesterFinal + $secondSemesterFinal) / 2;

                    file_put_contents(storage_path('logs/score-save.log'), "      Overall Final: $finalScore\n", FILE_APPEND);

                    // Update or create the final score record with month=NULL
                    DB::table('scores')->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'year' => $year,
                            'month' => null,
                        ],
                        [
                            'final_score' => round($finalScore, 2),
                            'grade' => $this->calculateGrade($finalScore),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                    $updatedCount++;
                    file_put_contents(storage_path('logs/score-save.log'), "      ✓ Updated Final Score\n", FILE_APPEND);
                }
            }

            file_put_contents(storage_path('logs/score-save.log'), "  Final scores updated: $updatedCount students\n", FILE_APPEND);

        } catch (\Exception $e) {
            file_put_contents(storage_path('logs/score-save.log'), "  ERROR in calculateAndSaveFinalScores: " . $e->getMessage() . "\n", FILE_APPEND);
        }
    }
}
