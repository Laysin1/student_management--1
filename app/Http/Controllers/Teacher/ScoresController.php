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
            $month = $request->input('month');
            $reportType = $request->input('report_type', 'monthly');
            $semester = $request->input('semester');

            $scoreField = 'final_score';

            if ($reportType == 'semester' && $semester == '1') {
                $scoreField = 'first_semester';
            } elseif ($reportType == 'semester' && $semester == '2') {
                $scoreField = 'second_semester';
            }

            $scores = $request->input($scoreField, []);
            $savedCount = 0;

            foreach ($scores as $studentId => $score) {
                if ($score !== null && $score !== '' && (float) $score > 0) {

                    $date = $month
                        ? Carbon::createFromDate(2026, (int) $month, 1)
                        : Carbon::createFromDate(2026, 1, 1);

                    $student = Student::with('class')->find($studentId);

                    if ($student) {
                        $gradeLevelRaw = $student->class->grade_level ?? null;

                        preg_match('/\d+/', (string) $gradeLevelRaw, $matches);

                        $gradeLevel = isset($matches[0]) ? (int) $matches[0] : null;

                        Grade::updateOrCreate(
                            [
                                'student_id' => $student->id,
                                'date' => $date,
                            ],
                            [
                                'subject_id' => 1,
                                'score' => (float) $score,
                                'total_score' => 100,
                                'grade' => $this->calculateGrade($score),
                                'grade_status' => $this->getGradeStatus($score),
                                'grade_level' => $gradeLevel,
                            ]
                        );

                        $savedCount++;
                    }
                }
            }

            return redirect()->back()->with('success', "✅ Scores saved! ($savedCount records)");
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
}
