<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScoresController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $classes = $user->teacher->classes ?? [];
        $selectedClassStudents = [];

        if (request('class_id')) {
            // Find class from the teacher's classes
            $classModel = $classes->firstWhere('id', request('class_id'));

            if ($classModel) {
                $selectedClassStudents = $classModel->students ?? [];
            }
        }

        return view('teacher.classes.scores', [
            'classes' => $classes,
            'selectedClassStudents' => $selectedClassStudents,
        ]);
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
            if ($reportType == 'semester' && $semester == '1') {
                $scoreField = 'first_semester';
            } elseif ($reportType == 'semester' && $semester == '2') {
                $scoreField = 'second_semester';
            }

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
                    $student = Student::find($studentId);

                    if ($student) {
                        file_put_contents(storage_path('logs/score-save.log'), "  Found: ID={$student->id}, StudentID={$student->student_id}\n", FILE_APPEND);

                        // Save grade
                        Grade::updateOrCreate(
                            [
                                'student_id' => $student->id,
                                'date' => $date,
                            ],
                            [
                                'subject_id' => 1,
                                'score' => (float)$score,
                                'total_score' => 100,
                                'grade' => $this->calculateGrade($score),
                                'grade_status' => $this->getGradeStatus($score),
                            ]
                        );

                        $savedCount++;
                        file_put_contents(storage_path('logs/score-save.log'), "  ✓ Saved\n", FILE_APPEND);
                    } else {
                        file_put_contents(storage_path('logs/score-save.log'), "  ✗ Student $studentId not found\n", FILE_APPEND);
                    }
                }
            }

            file_put_contents(storage_path('logs/score-save.log'), "Total saved: $savedCount\n\n", FILE_APPEND);

            return redirect()->back()->with('success', "✅ Scores saved! ($savedCount records)");
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
}
