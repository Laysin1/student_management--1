<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GradeReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        file_put_contents(storage_path('logs/student-grades.log'), "\n=== GRADE REPORT ===\nUser: {$user->id}, Student: " . ($student ? $student->id : 'NULL') . "\n", FILE_APPEND);

        // Get all grades for the student organized by month
        $gradesByMonth = [];
        $attendanceByMonth = [];

        if ($student) {
            // Get year from query parameter or use 2026
            $year = request()->query('year', 2026);

            // Query from scores table with teacher and subject info
            $allScores = DB::table('scores')
                ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
                ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
                ->where('scores.student_id', $student->id)
                ->where('scores.year', $year)
                ->select('scores.*', 'teachers.first_name', 'teachers.last_name', 'subjects.name as subject_name')
                ->orderBy('scores.month', 'asc')
                ->get();

            file_put_contents(storage_path('logs/student-grades.log'), "Student ID: {$student->id}, Year: $year, Total scores found: " . count($allScores) . "\n", FILE_APPEND);

            // Group scores by month
            foreach ($allScores as $score) {
                file_put_contents(storage_path('logs/student-grades.log'), "  Score: student_id=$score->student_id, month=$score->month, final_score=$score->final_score\n", FILE_APPEND);

                $month = $score->month;

                if (!isset($gradesByMonth[$month])) {
                    $gradesByMonth[$month] = [];
                }

                // Convert to object format expected by view
                $scoreObject = (object)[
                    'id' => $score->id,
                    'student_id' => $score->student_id,
                    'score' => $score->final_score ?? $score->first_semester ?? $score->second_semester,
                    'total_score' => 100,
                    'grade' => $score->grade,
                    'grade_status' => $this->getGradeStatus($score->grade),
                    'subject_name' => $score->subject_name,
                    'first_name' => $score->first_name,
                    'last_name' => $score->last_name,
                    'subject' => null
                ];

                $gradesByMonth[$month][] = $scoreObject;
                file_put_contents(storage_path('logs/student-grades.log'), "    ✓ Added to month $month\n", FILE_APPEND);
            }

            // Get attendance data by month if available
            for ($i = 1; $i <= 12; $i++) {
                $attendanceByMonth[$i] = '0/0';
            }
        } else {
            file_put_contents(storage_path('logs/student-grades.log'), "ERROR: No student found for user {$user->id}\n", FILE_APPEND);
        }

        file_put_contents(storage_path('logs/student-grades.log'), "Final count: " . collect($gradesByMonth)->sum(fn($m) => count($m)) . " scores\n\n", FILE_APPEND);

        return view('student.grade_report.index', [
            'gradesByMonth' => $gradesByMonth,
            'attendanceByMonth' => $attendanceByMonth,
            'student' => $student ?? (object)['student_id' => 'Not Found']
        ]);
    }

    private function getGradeStatus($grade)
    {
        $gradeMap = [
            'A' => 'Excellent',
            'B' => 'Very Good',
            'C' => 'Good',
            'D' => 'Satisfactory',
            'F' => 'Needs Improvement'
        ];

        return $gradeMap[$grade] ?? $grade;
    }
}
