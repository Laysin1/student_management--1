@extends('layouts.student')

@section('content')
<div class="py-8">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

    <h2 class="font-semibold text-2xl text-gray-800 mb-6">
        My Attendance
    </h2>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Present</p>
            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $presentCount }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Absent</p>
            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $absentCount }}
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Permission</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">
                {{ $permissionCount }}
            </p>
        </div>

        {{-- <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm">Attendance Rate</p>
            <p class="text-3xl font-bold text-purple-600 mt-2">
                {{ $attendanceRate }}%
            </p>
        </div> --}}

    </div>

    <!-- Attendance Table -->

    <div class="bg-white rounded-lg shadow overflow-hidden">

        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold">
                Attendance Records
            </h3>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            Date
                        </th>

                        <th class="px-6 py-4 text-center">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center">
                            Action
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($dailyRecords as $record)

                    <tr>

                        <td class="px-6 py-4">

                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('d M Y') }}

                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                            </div>

                        </td>

                        <td class="px-6 py-4 text-center">

                            @if($record->status=='present')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                Present
                            </span>

                            @elseif($record->status=='absent')

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                Absent
                            </span>

                            @else

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                Permission
                            </span>

                            @endif

                        </td>

                        <td class="px-6 py-4 text-center">

                            <button
                                onclick="toggleDetail('detail{{ $loop->index }}')"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">

                                Detail

                            </button>

                        </td>

                    </tr>

                    <!-- Hidden Detail -->

                    <tr
                        id="detail{{ $loop->index }}"
                        class="hidden bg-gray-50">

                        <td colspan="3" class="px-6 py-4">

                            <div class="bg-white border rounded-lg">

                                <table class="w-full">

                                    <thead class="bg-gray-100">
                                        <tr>

                                            <th class="px-4 py-3 text-left">
                                                Subject
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Teacher
                                            </th>

                                            <th class="px-4 py-3 text-center">
                                                Status
                                            </th>

                                            <th class="px-4 py-3 text-left">
                                                Remarks
                                            </th>

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

                                                {{ trim(($subjectRecord->teacher->first_name ?? '').' '.($subjectRecord->teacher->last_name ?? '')) ?: 'Teacher' }}

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
                        <td colspan="3" class="px-6 py-10 text-center text-gray-500">
                            No attendance records
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
</div>

<script>
function toggleDetail(id)
{
    let element=document.getElementById(id);

    element.classList.toggle('hidden');
}
</script>

@endsection
