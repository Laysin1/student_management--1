@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 mb-6">Attendance Management</h2>

        <!-- Tabs Navigation -->
        <div class="flex gap-4 mb-6 border-b border-gray-200">
            <a href="{{ route('teacher.classes.index') }}" class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-600">
                Classes
            </a>
            <a href="{{ route('teacher.classes.attend') }}" class="px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">
                Attendance
            </a>
            <a href="{{ route('teacher.classes.scores') }}" class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-600">
                Scores
            </a>
        </div>

        <!-- Class Filter + Date Picker -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Class</label>
                    <form method="GET" id="classForm" class="flex gap-4">
                        <select name="class_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 flex-1" onchange="document.getElementById('classForm').submit()">
                            <option value="">-- Choose a Class --</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                    Grade {{ $class->grade_level }} - {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                    <input type="date" id="attendanceDate" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" value="{{ request('date', date('Y-m-d')) }}">
                </div>
            </div>
        </div>

        <!-- Attendance Table -->
        @if(request('class_id'))
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <form method="POST" action="{{ route('teacher.classes.saveAttend') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <input type="hidden" name="attendance_date" id="attendanceDateInput" value="{{ request('date', date('Y-m-d')) }}">

                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student ID</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Present</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Absent</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Permission</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Remarks</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($selectedClassStudents as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $student->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $student->student_id ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="w-4 h-4">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="w-4 h-4">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="permission" class="w-4 h-4">
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <input type="text" name="remarks[{{ $student->id }}]" placeholder="Optional remarks" class="px-2 py-1 border border-gray-300 rounded text-xs w-full">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-6 border-t flex gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                            Save Attendance
                        </button>
                        <a href="{{ route('teacher.classes.attendanceReport') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded">
                            View Daily Report
                        </a>
                    </div>
                </form>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">Select a class to manage attendance</p>
            </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('attendanceDate')?.addEventListener('change', function() {
        document.getElementById('attendanceDateInput').value = this.value;
    });
</script>
    </div>
</div>
@endsection
