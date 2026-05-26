<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $student = Student::with('class')
            ->where('user_id', $user->id)
            ->first();

        $gradesByMonth = [];
        $attendanceByMonth = [];
        $semester1Scores = collect();
        $semester2Scores = collect();

        if ($student) {
            $currentGradeRaw = $student->class->grade_level ?? 7;
            preg_match('/\d+/', (string) $currentGradeRaw, $matches);
            $currentGrade = isset($matches[0]) ? (int) $matches[0] : 7;

            $selectedGrade = (int) request()->query('grade_level', $currentGrade);

            // Monthly scores
            $allScores = DB::table('scores')
                ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
                ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
                ->where('scores.student_id', $student->id)
                ->where('scores.grade_level', $selectedGrade)
                ->whereNotNull('scores.month')
                ->whereNotNull('scores.final_score')
                ->select(
                    'scores.*',
                    'teachers.first_name',
                    'teachers.last_name',
                    'subjects.name as subject_name'
                )
                ->orderBy('scores.month', 'asc')
                ->get();

            foreach ($allScores as $score) {
                $month = (int) $score->month;

                if (!isset($gradesByMonth[$month])) {
                    $gradesByMonth[$month] = [];
                }

                $gradesByMonth[$month][] = (object) [
                    'id' => $score->id,
                    'student_id' => $score->student_id,
                    'teacher_id' => $score->teacher_id,
                    'month' => $score->month,
                    'grade_level' => $score->grade_level,
                    'score' => $score->final_score,
                    'total_score' => 100,
                    'grade' => $score->grade,
                    'subject_name' => $score->subject_name ?? 'General Score',
                    'first_name' => $score->first_name,
                    'last_name' => $score->last_name,
                ];
            }

            // S1 exam scores
            $semester1Scores = DB::table('scores')
                ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
                ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
                ->where('scores.student_id', $student->id)
                ->where('scores.grade_level', $selectedGrade)
                ->whereNotNull('scores.first_semester')
                ->select(
                    'scores.id',
                    'scores.first_semester as score',
                    'scores.grade',
                    'teachers.first_name',
                    'teachers.last_name',
                    'subjects.name as subject_name'
                )
                ->get();

            // S2 exam scores
            $semester2Scores = DB::table('scores')
                ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
                ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
                ->where('scores.student_id', $student->id)
                ->where('scores.grade_level', $selectedGrade)
                ->whereNotNull('scores.second_semester')
                ->select(
                    'scores.id',
                    'scores.second_semester as score',
                    'scores.grade',
                    'teachers.first_name',
                    'teachers.last_name',
                    'subjects.name as subject_name'
                )
                ->get();

            for ($i = 1; $i <= 12; $i++) {
                $attendanceByMonth[$i] = '0/0';
            }
        }

        return view('student.grade_report.index', [
            'gradesByMonth' => $gradesByMonth,
            'attendanceByMonth' => $attendanceByMonth,
            'semester1Scores' => $semester1Scores,
            'semester2Scores' => $semester2Scores,
            'student' => $student ?? (object) ['student_id' => 'Not Found'],
        ]);
    }
}
