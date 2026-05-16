@extends('layouts.student')

@section('content')
@php
    $currentGradeRaw = $student->class->grade_level ?? 7;
    preg_match('/\d+/', (string) $currentGradeRaw, $matches);

    $currentGrade = isset($matches[0]) ? (int) $matches[0] : 7;
    $selectedGrade = (int) request('grade_level', $currentGrade);

    $availableGrades = [6, 7, 8, 9, 10, 11, 12];

    $months = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    $monthNumbers = [1,2,3,4,5,6,7,8,9,10,11,12];

    $getGradeLetter = function ($score) {
        if ($score === null) return 'N/A';
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    };
@endphp

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    <p class="text-sm font-semibold text-blue-600 mb-1">
                        Student Academic Portal
                    </p>

                    <h1 class="text-3xl font-bold text-gray-900">
                        Grade Report
                    </h1>

                    <p class="text-sm text-gray-500 mt-2">
                        Student ID: {{ $student->student_id ?? 'N/A' }}
                        |
                        Total Grades: {{ collect($gradesByMonth)->sum(fn($m) => count($m)) }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <label class="text-sm font-bold text-gray-700">
                        Grade:
                    </label>

                    <select id="gradeSelect"
                            class="px-5 py-2.5 min-w-[220px] border border-gray-300 rounded-xl bg-white text-gray-900 font-semibold focus:outline-none focus:border-blue-500 pr-10"
                        @foreach($availableGrades as $grade)
                            <option value="{{ $grade }}" {{ $selectedGrade === $grade ? 'selected' : '' }}>
                                Grade {{ $grade }} {{ $grade === $currentGrade ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Monthly Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-20">
            @foreach($months as $index => $month)
                @php
                    $monthNum = $monthNumbers[$index];
                    $grades = $gradesByMonth[$monthNum] ?? [];
                    $hasData = count($grades) > 0;
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="@if($hasData) bg-blue-50 @else bg-gray-50 @endif px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $month }}
                        </h3>

                        @if(!$hasData)
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-50 border border-yellow-200 text-xs font-semibold text-yellow-700">
                                Awaiting input
                            </span>
                        @endif
                    </div>

                    @if($hasData)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">No.</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Subject</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Teacher</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Score</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Grade</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-200">
                                    @foreach($grades as $index => $grade)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-900 font-medium">
                                                {{ $index + 1 }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-900">
                                                {{ $grade->subject_name ?? 'General Score' }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-900">
                                                {{ trim(($grade->first_name ?? '') . ' ' . ($grade->last_name ?? '')) ?: '—' }}
                                            </td>

                                            <td class="px-4 py-3 text-center text-gray-900 font-bold">
                                                {{ $grade->score }}/{{ $grade->total_score ?? 100 }}
                                            </td>

                                            <td class="px-4 py-3 text-center font-bold
                                                @if($grade->grade === 'A') text-green-600
                                                @elseif($grade->grade === 'B') text-blue-600
                                                @elseif($grade->grade === 'C') text-yellow-600
                                                @elseif($grade->grade === 'D') text-orange-600
                                                @else text-red-600
                                                @endif">
                                                {{ $grade->grade }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Total Attendance</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $attendanceByMonth[$monthNum] ?? '0/0' }}
                                </p>
                            </div>

                            <div class="text-right">
                                <p class="text-gray-600">Average Score</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ number_format(collect($grades)->avg('score'), 2) }}
                                </p>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16">
                            <p class="text-lg font-bold text-gray-900 mb-1">
                                No data available
                            </p>

                            <p class="text-sm text-gray-500">
                                No grades found for Grade {{ $selectedGrade }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Semester Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
            @php
                $allScores = collect($gradesByMonth)->flatten(1);

                $semester1Months = [12, 1, 2, 3];
                $semester2Months = [4, 5, 6, 7];

                $semester1Scores = $allScores->whereIn('month', $semester1Months);
                $semester2Scores = $allScores->whereIn('month', $semester2Months);

                $semester1Average = $semester1Scores->count() > 0
                    ? round($semester1Scores->avg('score'), 2)
                    : null;

                $semester2Average = $semester2Scores->count() > 0
                    ? round($semester2Scores->avg('score'), 2)
                    : null;

                $finalAverage = ($semester1Average !== null && $semester2Average !== null)
                    ? round(($semester1Average + $semester2Average) / 2, 2)
                    : null;

                $semesterCards = [
                    [
                        'title' => 'Semester 1',
                        'score' => $semester1Average,
                        'formula' => 'Average: Dec → Mar',
                    ],
                    [
                        'title' => 'Semester 2',
                        'score' => $semester2Average,
                        'formula' => 'Average: Apr → Jul',
                    ],
                    [
                        'title' => 'Final Score',
                        'score' => $finalAverage,
                        'formula' => '(Semester 1 + Semester 2) / 2',
                    ],
                ];
            @endphp

            @foreach($semesterCards as $card)
                @php
                    $hasData = $card['score'] !== null;
                    $grade = $getGradeLetter($card['score']);
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="@if($hasData) bg-purple-50 @else bg-gray-50 @endif px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $card['title'] }}
                        </h3>

                        <p class="text-xs text-gray-600 mt-1">
                            {{ $card['formula'] }}
                        </p>
                    </div>

                    @if($hasData)
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500 mb-2">
                                Score
                            </p>

                            <h2 class="text-5xl font-bold text-gray-900 mb-3">
                                {{ number_format($card['score'], 2) }}
                            </h2>

                            <span class="inline-flex px-4 py-2 rounded-full bg-gray-100 text-sm font-bold
                                @if($grade === 'A') text-green-600
                                @elseif($grade === 'B') text-blue-600
                                @elseif($grade === 'C') text-yellow-600
                                @elseif($grade === 'D') text-orange-600
                                @else text-red-600
                                @endif">
                                Grade {{ $grade }}
                            </span>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-16">
                            <p class="text-lg font-bold text-gray-900 mb-1">
                                No data available
                            </p>

                            <p class="text-sm text-gray-500">
                                Semester score will appear when monthly grades are submitted
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="fixed bottom-0 left-64 right-0 bg-white border-t-2 border-blue-500 px-6 py-3">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                          clip-rule="evenodd"/>
                </svg>

                <p class="text-sm text-gray-700">
                    Semester is coming soon
                </p>
            </div>
        </div>

    </div>
</div>

<script>
    document.getElementById('gradeSelect').addEventListener('change', function () {
        const grade = this.value;
        window.location.href = `{{ route('student.scores') }}?grade_level=${grade}`;
    });
</script>
@endsection
