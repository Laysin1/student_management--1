@extends('layouts.parent')

@section('content')

@php
    $currentGradeRaw = $student->class->grade_level ?? 7;
    preg_match('/\d+/', (string) $currentGradeRaw, $matches);

    $currentGrade = isset($matches[0]) ? (int) $matches[0] : 7;
    $selectedGrade = (int) request('grade_level', $currentGrade);

    $availableGrades = [7, 8, 9, 10, 11, 12];

    $months = [
        'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August',
        'September', 'October', 'November', 'December'
    ];

    $monthNumbers = [1,2,3,4,5,6,7,8,9,10,11,12];

    $gradesByMonth = [];

    $scores = DB::table('scores')
        ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
        ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
        ->where('scores.student_id', $student->id)
        ->where('scores.grade_level', $selectedGrade)
        ->select(
            'scores.*',
            'teachers.first_name',
            'teachers.last_name',
            'subjects.name as subject_name'
        )
        ->orderBy('scores.month', 'asc')
        ->get();

    foreach ($scores as $score) {
        $month = (int) $score->month;

        if (!isset($gradesByMonth[$month])) {
            $gradesByMonth[$month] = [];
        }

        $gradesByMonth[$month][] = $score;
    }
@endphp

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm font-semibold text-blue-600 mb-1">
                        Parent Academic Portal
                    </p>

                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-2">
                        Student ID: {{ $student->student_id ?? 'N/A' }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="bg-blue-600 text-white px-6 py-4 rounded-2xl shadow-sm">
                        <p class="text-xs text-blue-100 uppercase tracking-wide font-semibold">
                            Viewing Grade
                        </p>

                        <p class="text-2xl font-bold">
                            Grade {{ $selectedGrade }}
                        </p>
                    </div>

                    <a href="{{ route('parent.classes') }}"
                       class="inline-flex items-center justify-center bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-5 rounded-xl">
                        ← Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Grade Filter -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Academic History
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Select a grade to view the student’s previous or current academic report.
                    </p>
                </div>

                <form method="GET">
                    <select name="grade_level"
                            onchange="this.form.submit()"
                            class="w-full sm:w-64 rounded-xl border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 font-semibold">
                        @foreach($availableGrades as $grade)
                            <option value="{{ $grade }}" {{ $selectedGrade === $grade ? 'selected' : '' }}>
                                Grade {{ $grade }} {{ $grade === $currentGrade ? '(Current)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </form>
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
                            <span class="px-3 py-1 rounded-full bg-yellow-50 border border-yellow-200 text-xs font-semibold text-yellow-700">
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
                                            <td class="px-4 py-3 font-medium text-gray-900">
                                                {{ $index + 1 }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-900">
                                                {{ $grade->subject_name ?? 'General Score' }}
                                            </td>

                                            <td class="px-4 py-3 text-gray-900">
                                                {{ trim(($grade->first_name ?? '') . ' ' . ($grade->last_name ?? '')) ?: '—' }}
                                            </td>

                                            <td class="px-4 py-3 text-center font-bold text-gray-900">
                                                {{ $grade->final_score }}/100
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

                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 text-right">
                            <p class="text-sm text-gray-500">Average Score</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ number_format(collect($grades)->avg('final_score'), 2) }}
                            </p>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-14">
                            <p class="text-lg font-bold text-gray-900">
                                No data available
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                No scores found for Grade {{ $selectedGrade }}
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Semester Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
            @php
                $monthlyAverages = [];

                foreach ($gradesByMonth as $monthNumber => $grades) {
                    $monthlyAverages[$monthNumber] = collect($grades)->avg('final_score');
                }

                $semester1Values = collect($monthlyAverages)->only([12, 1, 2, 3])->filter(fn($value) => $value !== null);
                $semester2Values = collect($monthlyAverages)->only([4, 5, 6, 7])->filter(fn($value) => $value !== null);

                $semester1Average = $semester1Values->count() > 0 ? round($semester1Values->avg(), 2) : null;
                $semester2Average = $semester2Values->count() > 0 ? round($semester2Values->avg(), 2) : null;

                $finalAverage = ($semester1Average !== null && $semester2Average !== null)
                    ? round(($semester1Average + $semester2Average) / 2, 2)
                    : null;

                $semesterCards = [
                    ['title' => 'Semester 1', 'score' => $semester1Average, 'months' => 'Dec → Mar'],
                    ['title' => 'Semester 2', 'score' => $semester2Average, 'months' => 'Apr → Jul'],
                    ['title' => 'Final Score', 'score' => $finalAverage, 'months' => '(S1 + S2) ÷ 2'],
                ];
            @endphp

            @foreach($semesterCards as $card)
                @php
                    $hasData = $card['score'] !== null;

                    if (!$hasData) {
                        $grade = 'N/A';
                        $gradeColor = 'text-gray-500';
                    } elseif ($card['score'] >= 90) {
                        $grade = 'A';
                        $gradeColor = 'text-green-600';
                    } elseif ($card['score'] >= 80) {
                        $grade = 'B';
                        $gradeColor = 'text-blue-600';
                    } elseif ($card['score'] >= 70) {
                        $grade = 'C';
                        $gradeColor = 'text-yellow-600';
                    } elseif ($card['score'] >= 60) {
                        $grade = 'D';
                        $gradeColor = 'text-orange-600';
                    } else {
                        $grade = 'F';
                        $gradeColor = 'text-red-600';
                    }
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="@if($hasData) bg-purple-50 @else bg-gray-50 @endif px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-bold text-gray-900">
                            {{ $card['title'] }}
                        </h3>

                        <p class="text-xs text-gray-500 mt-1">
                            {{ $card['months'] }}
                        </p>
                    </div>

                    @if($hasData)
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500 mb-2">
                                Average Score
                            </p>

                            <h2 class="text-5xl font-bold text-gray-900 mb-3">
                                {{ number_format($card['score'], 2) }}
                            </h2>

                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-gray-100 text-sm font-bold {{ $gradeColor }}">
                                Grade {{ $grade }}
                            </span>
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-14">
                            <p class="text-lg font-bold text-gray-900">
                                No data available
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                Semester grades will appear when submitted
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
