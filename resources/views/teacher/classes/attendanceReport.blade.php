@extends('layouts.teacher')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Daily Attendance Report
            </h1>
            <p class="text-gray-600 mt-1">
                View attendance records by class, date, or status.
            </p>
        </div>

        <a href="{{ route('teacher.classes.attend') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-5 py-2.5 rounded-lg text-center">
            ← Back to Attendance
        </a>
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 p-5 mb-6">
        <form method="GET" action="{{ route('teacher.classes.attendanceReport') }}">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Class</label>
                    <select name="class_id" class="border border-gray-300 rounded-lg px-4 py-2.5 w-full">
                        <option value="">Choose class</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->grade_level }} - {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input type="date"
                           name="attendance_date"
                           value="{{ request('attendance_date') }}"
                           class="border border-gray-300 rounded-lg px-4 py-2.5 w-full">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2.5 w-full">
                        <option value="">All Status</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="permission" {{ request('status') == 'permission' ? 'selected' : '' }}>Permission</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search Student</label>
                    <input type="text"
                           id="studentSearch"
                           placeholder="Search name or ID..."
                           class="border border-gray-300 rounded-lg px-4 py-2.5 w-full">
                </div>

            </div>

            <div class="flex flex-col md:flex-row gap-3 mt-5">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg">
                    Apply Filter
                </button>

                <a href="{{ route('teacher.classes.attendanceReport') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-2.5 rounded-lg text-center">
                    Clear
                </a>
            </div>
        </form>
    </div>

    @if(request('class_id'))

        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

            <div class="p-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-900">Attendance Records</h2>
                <p class="text-gray-600 text-sm mt-1">
                    Results based on your selected filters.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Student ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Remarks</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($attendanceRecords as $record)
                            <tr class="hover:bg-gray-50 attendance-row">
                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $record->student->first_name ?? '' }} {{ $record->student->last_name ?? '' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $record->student->student_id ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $record->attendance_date->format('d M Y') }}
                                </td>

                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $record->status === 'present' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $record->status === 'absent' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $record->status === 'permission' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $record->remarks ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    No attendance records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div class="mt-6">
            {{ $attendanceRecords->appends(request()->query())->links() }}
        </div>

    @else

        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">
            <div class="text-gray-400 text-lg mb-1">No class selected</div>
            <p class="text-gray-500 text-sm">
                Please choose a class first to view the attendance report.
            </p>
        </div>

    @endif

</div>

<script>
    const studentSearch = document.getElementById('studentSearch');

    studentSearch?.addEventListener('keyup', function () {
        const value = this.value.toLowerCase();

        document.querySelectorAll('.attendance-row').forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>
@endsection
