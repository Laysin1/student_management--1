@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 mb-6">Student Scores Management</h2>

        <!-- Tabs Navigation -->
        <div class="flex gap-4 mb-6 border-b border-gray-200">
            <a href="{{ route('teacher.classes.index') }}" class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-600">
                Classes
            </a>
            <a href="{{ route('teacher.classes.attend') }}" class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-600">
                Attendance
            </a>
            <a href="{{ route('teacher.classes.scores') }}" class="px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">
                Scores
            </a>
        </div>

        <!-- Class + Month + Report Type Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Month</label>
                    <form method="GET" id="monthForm" class="flex gap-4">
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                        <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                        <input type="hidden" name="semester" value="{{ request('semester') }}">
                        <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 flex-1" onchange="document.getElementById('monthForm').submit()">
                            <option value="">-- Choose Month --</option>
                            <option value="1" {{ request('month') == 1 ? 'selected' : '' }}>January</option>
                            <option value="2" {{ request('month') == 2 ? 'selected' : '' }}>February</option>
                            <option value="3" {{ request('month') == 3 ? 'selected' : '' }}>March</option>
                            <option value="4" {{ request('month') == 4 ? 'selected' : '' }}>April</option>
                            <option value="5" {{ request('month') == 5 ? 'selected' : '' }}>May</option>
                            <option value="6" {{ request('month') == 6 ? 'selected' : '' }}>June</option>
                            <option value="7" {{ request('month') == 7 ? 'selected' : '' }}>July</option>
                            <option value="8" {{ request('month') == 8 ? 'selected' : '' }}>August</option>
                            <option value="9" {{ request('month') == 9 ? 'selected' : '' }}>September</option>
                            <option value="10" {{ request('month') == 10 ? 'selected' : '' }}>October</option>
                            <option value="11" {{ request('month') == 11 ? 'selected' : '' }}>November</option>
                            <option value="12" {{ request('month') == 12 ? 'selected' : '' }}>December</option>
                        </select>
                    </form>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <form method="GET" id="reportForm" class="flex gap-4">
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="semester" value="{{ request('semester') }}">
                        <select name="report_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 flex-1" onchange="document.getElementById('reportForm').submit()">
                            <option value="">-- Choose Type --</option>
                            <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly Scores</option>
                            <option value="semester" {{ request('report_type') == 'semester' ? 'selected' : '' }}>Semester Scores</option>
                            <option value="final" {{ request('report_type') == 'final' ? 'selected' : '' }}>Final Scores</option>
                        </select>
                    </form>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <form method="GET" id="semesterForm" class="flex gap-4">
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                        <input type="hidden" name="month" value="{{ request('month') }}">
                        <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                        <select name="semester" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 flex-1" onchange="document.getElementById('semesterForm').submit()">
                            <option value="">-- All --</option>
                            <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>1st Semester</option>
                            <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>2nd Semester</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scores Table -->
        @if(request('class_id') && request('month'))
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <form method="POST" action="{{ route('teacher.classes.saveScores') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <input type="hidden" name="month" value="{{ request('month') }}">
                    <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                    <input type="hidden" name="semester" value="{{ request('semester') }}">

                    <div class="bg-blue-50 px-6 py-3 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">
                            {{ date('F Y', mktime(0, 0, 0, request('month'), 1)) }}
                            @if(request('report_type') == 'semester' && request('semester') == '1')
                                - 1st Semester Scores
                            @elseif(request('report_type') == 'semester' && request('semester') == '2')
                                - 2nd Semester Scores
                            @elseif(request('report_type') == 'final')
                                - Final Scores
                            @else
                                - Monthly Scores
                            @endif
                        </h3>
                    </div>

                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student ID</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Score</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($selectedClassStudents as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $student->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $student->student_id ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $student->user->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($student->gender ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if(request('report_type') == 'semester' && request('semester') == '1')
                                            <input type="number" name="first_semester[{{ $student->id }}]" min="0" max="100" placeholder="0-100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        @elseif(request('report_type') == 'semester' && request('semester') == '2')
                                            <input type="number" name="second_semester[{{ $student->id }}]" min="0" max="100" placeholder="0-100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        @else
                                            <input type="number" name="final_score[{{ $student->id }}]" min="0" max="100" placeholder="0-100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No students in this class.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="p-6 border-t flex gap-4">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                            Save Scores for {{ date('F Y', mktime(0, 0, 0, request('month'), 1)) }}
                        </button>
                        <a href="{{ route('teacher.classes.scoresReport') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded">
                            View Monthly Report
                        </a>
                    </div>
                </form>
            </div>

            <script>
                function calculateGrade(studentId) {
                    const finalScoreInput = document.querySelector(`input[name="final_score[${studentId}]"]`);
                    const score = parseInt(finalScoreInput.value) || 0;
                    const gradeSpan = document.getElementById(`grade-${studentId}`);

                    let grade = '—';
                    if (score >= 90) grade = 'A';
                    else if (score >= 80) grade = 'B';
                    else if (score >= 70) grade = 'C';
                    else if (score >= 60) grade = 'D';
                    else if (score > 0) grade = 'F';

                    gradeSpan.textContent = grade;
                }
            </script>
        @elseif(request('class_id'))
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">Select a month to enter scores</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">Select a class and month to manage scores</p>
            </div>
        @endif
    </div>
</div>
@endsection
