@extends('layouts.parent')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Attendance - {{ $student->first_name }} {{ $student->last_name }}</h1>
            <a href="{{ route('parent.classes') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                ← Back
            </a>
        </div>

        <!-- Attendance Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
            @php
                $totalAttendance = DB::table('attendances')
                    ->where('student_id', $student->id)
                    ->count();
                $presentCount = DB::table('attendances')
                    ->where('student_id', $student->id)
                    ->where('status', 'present')
                    ->count();
                $absentCount = DB::table('attendances')
                    ->where('student_id', $student->id)
                    ->where('status', 'absent')
                    ->count();
                $permissionCount = DB::table('attendances')
                    ->where('student_id', $student->id)
                    ->where('status', 'permission')
                    ->count();
                $attendanceRate = $totalAttendance > 0 ? (($presentCount + $permissionCount) / $totalAttendance) * 100 : 0;
            @endphp

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Total Days</div>
                <div class="mt-2 text-2xl font-bold text-blue-700">{{ $totalAttendance }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Present</div>
                <div class="mt-2 text-2xl font-bold text-green-700">{{ $presentCount }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Absent</div>
                <div class="mt-2 text-2xl font-bold text-red-700">{{ $absentCount }}</div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Permission</div>
                <div class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $permissionCount }}
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Attendance Rate</div>
                <div class="mt-2 text-2xl font-bold text-yellow-700">{{ number_format($attendanceRate, 1) }}%</div>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse(DB::table('attendances')->where('student_id', $student->id)->orderBy('attendance_date', 'desc')->get() as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-sm">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $record->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $record->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $record->status === 'permission' ? 'bg-blue-100 text-blue-800' : '' }}
                                ">
                                    {{ ucfirst($record->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $record->remarks ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
