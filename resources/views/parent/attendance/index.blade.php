@extends('layouts.parent')

@section('content')
@php
    $rawRecords = \App\Models\Attendance::with([
        'teacher',
        'teacher.subject',
        'schedule'
    ])
    ->where('student_id', $student->id)
    ->orderBy('attendance_date', 'desc')
    ->get();

    $dailyRecords = $rawRecords
        ->groupBy(function ($record) {
            return \Carbon\Carbon::parse($record->attendance_date)->format('Y-m-d');
        })
        ->map(function ($dayRecords) {
            $statuses = $dayRecords->pluck('status')->map(fn($s) => strtolower($s));

            if ($statuses->contains('absent')) {
                $finalStatus = 'absent';
            } elseif ($statuses->every(fn($s) => $s === 'permission')) {
                $finalStatus = 'permission';
            } else {
                $finalStatus = 'present';
            }

            return (object) [
                'attendance_date' => $dayRecords->first()->attendance_date,
                'status' => $finalStatus,
                'subjects' => $dayRecords,
            ];
        });

    $totalAttendance = $dailyRecords->count();
    $presentCount = $dailyRecords->where('status', 'present')->count();
    $absentCount = $dailyRecords->where('status', 'absent')->count();
    $permissionCount = $dailyRecords->where('status', 'permission')->count();

    $attendanceRate = $totalAttendance > 0
        ? (($presentCount + $permissionCount) / $totalAttendance) * 100
        : 0;
@endphp

<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">
                Attendance - {{ $student->first_name }} {{ $student->last_name }}
            </h1>

            <a href="{{ route('parent.classes') }}"
               class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                ← Back
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Total Days</div>
                <div class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $totalAttendance }}
                </div>
            </div> --}}

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Present</div>
                <div class="mt-2 text-2xl font-bold text-green-700">
                    {{ $presentCount }}
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Absent</div>
                <div class="mt-2 text-2xl font-bold text-red-700">
                    {{ $absentCount }}
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Permission</div>
                <div class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $permissionCount }}
                </div>
            </div>

            {{-- <div class="bg-white shadow rounded-lg p-6">
                <div class="text-gray-500">Attendance Rate</div>
                <div class="mt-2 text-2xl font-bold text-yellow-700">
                    {{ number_format($attendanceRate, 1) }}%
                </div>
            </div> --}}
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">
                            Date
                        </th>

                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                            Final Status
                        </th>

                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">
                            Detail
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse($dailyRecords as $record)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}
                                <div class="text-xs text-gray-400">
                                    {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-center">
                                @if($record->status === 'present')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Present
                                    </span>
                                @elseif($record->status === 'absent')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Absent
                                    </span>
                                @elseif($record->status === 'permission')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                        Permission
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button type="button"
                                        onclick="toggleDetail('detail{{ $loop->index }}')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                    Detail
                                </button>
                            </td>
                        </tr>

                        <tr id="detail{{ $loop->index }}" class="hidden bg-gray-50">
                            <td colspan="3" class="px-6 py-4">
                                <div class="bg-white border rounded-lg overflow-hidden">
                                    <table class="w-full text-sm">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="px-4 py-3 text-left">Subject</th>
                                                <th class="px-4 py-3 text-left">Teacher</th>
                                                <th class="px-4 py-3 text-center">Status</th>
                                                <th class="px-4 py-3 text-left">Remarks</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($record->subjects as $subjectRecord)
                                                <tr class="border-t">
                                                    <td class="px-4 py-3">
    @php
        $subjectName = DB::table('subjects')
            ->where('id', $subjectRecord->teacher->subject_id ?? null)
            ->value('name');
    @endphp

    {{ $subjectName ?? 'No Subject Assigned' }}
</td>

                                                    <td class="px-4 py-3">
                                                        {{ trim(($subjectRecord->teacher->first_name ?? '') . ' ' . ($subjectRecord->teacher->last_name ?? '')) ?: 'Teacher' }}
                                                    </td>

                                                    <td class="px-4 py-3 text-center">
                                                        {{ ucfirst($subjectRecord->status) }}
                                                    </td>

                                                    <td class="px-4 py-3">
                                                        {{ $subjectRecord->remarks ?? '—' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                                No attendance records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

<script>
function toggleDetail(id) {
    const row = document.getElementById(id);
    row.classList.toggle('hidden');
}
</script>
@endsection
