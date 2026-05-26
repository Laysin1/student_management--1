<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoresController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $teacher = $user->teacher;

        $classes = $teacher ? $teacher->classes : collect();
        $selectedClassStudents = collect();

        if (request('class_id')) {
            $classModel = $classes->firstWhere('id', request('class_id'));

            if ($classModel) {
                $selectedClassStudents = $classModel->students ?? collect();
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
            $user = Auth::user();
            $teacher = $user->teacher;

            if (!$teacher) {
                return redirect()->back()
                    ->with('error', 'Teacher profile not found.');
            }

            $month = (int) $request->input('month');

            $scoreField = 'final_score';
            $scores = $request->input($scoreField, []);

            $savedCount = 0;

            foreach ($scores as $studentId => $score) {

                if ($score === null || $score === '' || (float)$score < 0) {
                    continue;
                }

                $student = Student::with('class')->find($studentId);

                if (!$student || !$student->class) {
                    continue;
                }

                $gradeLevelRaw = $student->class->grade_level ?? null;

                preg_match('/\d+/', (string)$gradeLevelRaw, $matches);

                $gradeLevel = isset($matches[0])
                    ? (int)$matches[0]
                    : null;

                // Check if this exact teacher already scored this month
                $existing = DB::table('scores')
                    ->where('student_id', $student->id)
                    ->where('teacher_id', $teacher->id)
                    ->where('class_id', $student->class->id)
                    ->where('month', $month)
                    ->first();

                if ($existing) {

                    DB::table('scores')
                        ->where('id', $existing->id)
                        ->update([
                            'final_score' => (float)$score,
                            'grade_level' => $gradeLevel,
                            'grade' => $this->calculateGrade($score),
                            'updated_at' => now(),
                        ]);

                } else {

                    DB::table('scores')->insert([
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'class_id' => $student->class->id,
                        'grade_level' => $gradeLevel,
                        'month' => $month,
                        'final_score' => (float)$score,
                        'grade' => $this->calculateGrade($score),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                }

                $savedCount++;
            }

            return redirect()->back()
                ->with('success', "✅ Scores saved! ($savedCount records)");

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', "❌ Error: " . $e->getMessage());
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
