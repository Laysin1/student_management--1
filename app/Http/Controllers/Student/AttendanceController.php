<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $student = Student::where('user_id', Auth::id())->first();

        if (!$student) {
            return back()->with('error', 'Student not found');
        }

        $records = Attendance::with([
    'teacher',
    'teacher.subject',
    'schedule'
])
        ->where('student_id', $student->id)
        ->orderBy('attendance_date', 'desc')
        ->get();

        // Cambodia attendance rule
        $dailyRecords = $records
            ->groupBy(function ($record) {
                return $record->attendance_date->format('Y-m-d');
            })
            ->map(function ($dayRecords) {

                $statuses = $dayRecords
                    ->pluck('status')
                    ->map(fn($s) => strtolower($s));

                // If even one subject absent → whole day absent
                if ($statuses->contains('absent')) {
                    $finalStatus = 'absent';
                }

                // All permission → permission
                elseif (
                    $statuses->every(
                        fn($s) => $s === 'permission'
                    )
                ) {
                    $finalStatus = 'permission';
                }

                // Mixed permission + present OR all present
                else {
                    $finalStatus = 'present';
                }

                return (object)[
                    'attendance_date' => $dayRecords->first()->attendance_date,
                    'status' => $finalStatus,
                    'subjects' => $dayRecords
                ];
            });

        $presentCount = $dailyRecords
            ->where('status','present')
            ->count();

        $absentCount = $dailyRecords
            ->where('status','absent')
            ->count();

        $permissionCount = $dailyRecords
            ->where('status','permission')
            ->count();

        $totalDays = $dailyRecords->count();

        $attendanceRate = $totalDays > 0
            ? round(
                (($presentCount + $permissionCount) / $totalDays) * 100,
                2
            )
            : 0;

        return view(
            'student.attendance.index',
            compact(
                'dailyRecords',
                'presentCount',
                'absentCount',
                'permissionCount',
                'attendanceRate'
            )
        );
    }
}
