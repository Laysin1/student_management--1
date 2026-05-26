@extends('layouts.parent')

@section('content')
@php
    $currentGradeRaw = $student->class->grade_level ?? 7;
    preg_match('/\d+/', (string) $currentGradeRaw, $matches);

    $currentGrade = isset($matches[0]) ? (int) $matches[0] : 7;
    $selectedGrade = (int) request('grade_level', $currentGrade);

    $availableGrades = [7, 8, 9, 10, 11, 12];

    $months = [
        12 => 'December',
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10 => 'October',
        11 => 'November',
    ];

    $getGradeLetter = function ($score) {
        if ($score === null) return 'N/A';
        if ($score >= 90) return 'A';
        if ($score >= 80) return 'B';
        if ($score >= 70) return 'C';
        if ($score >= 60) return 'D';
        return 'F';
    };

    $gradesByMonth = [];

    $monthlyScores = DB::table('scores')
        ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
        ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
        ->where('scores.student_id', $student->id)
        ->where('scores.grade_level', $selectedGrade)
        ->whereNotNull('scores.month')
        ->whereNotNull('scores.final_score')
        ->select(
            'scores.*',
            'teachers.first_name',
            'teachers.last_name',
            'subjects.name as subject_name'
        )
        ->orderBy('scores.month', 'asc')
        ->get();

    foreach ($monthlyScores as $score) {
        $month = (int) $score->month;

        if (!isset($gradesByMonth[$month])) {
            $gradesByMonth[$month] = [];
        }

        $gradesByMonth[$month][] = (object) [
            'id' => $score->id,
            'month' => $score->month,
            'score' => $score->final_score,
            'grade' => $score->grade,
            'subject_name' => $score->subject_name ?? 'General Score',
            'first_name' => $score->first_name,
            'last_name' => $score->last_name,
        ];
    }

    $semester1Scores = DB::table('scores')
        ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
        ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
        ->where('scores.student_id', $student->id)
        ->where('scores.grade_level', $selectedGrade)
        ->whereNotNull('scores.first_semester')
        ->select(
            'scores.first_semester as score',
            'scores.grade',
            'teachers.first_name',
            'teachers.last_name',
            'subjects.name as subject_name'
        )
        ->get();

    $semester2Scores = DB::table('scores')
        ->leftJoin('teachers', 'scores.teacher_id', '=', 'teachers.id')
        ->leftJoin('subjects', 'teachers.subject_id', '=', 'subjects.id')
        ->where('scores.student_id', $student->id)
        ->where('scores.grade_level', $selectedGrade)
        ->whereNotNull('scores.second_semester')
        ->select(
            'scores.second_semester as score',
            'scores.grade',
            'teachers.first_name',
            'teachers.last_name',
            'subjects.name as subject_name'
        )
        ->get();

    $allScores = collect($gradesByMonth)->flatten(1);

    $s1Monthly = $allScores->whereIn('month', [12, 1, 2, 3]);
    $s2Monthly = $allScores->whereIn('month', [5, 6, 7]);

    $s1MonthlyAvg = $s1Monthly->count() ? round($s1Monthly->avg('score'), 2) : null;
    $s2MonthlyAvg = $s2Monthly->count() ? round($s2Monthly->avg('score'), 2) : null;

    $s1ExamAvg = $semester1Scores->count() ? round($semester1Scores->avg('score'), 2) : null;
    $s2ExamAvg = $semester2Scores->count() ? round($semester2Scores->avg('score'), 2) : null;

    $finalS1 = ($s1MonthlyAvg !== null && $s1ExamAvg !== null)
        ? round(($s1MonthlyAvg + $s1ExamAvg) / 2, 2)
        : null;

    $finalS2 = ($s2MonthlyAvg !== null && $s2ExamAvg !== null)
        ? round(($s2MonthlyAvg + $s2ExamAvg) / 2, 2)
        : null;

    $finalScore = ($finalS1 !== null && $finalS2 !== null)
        ? round(($finalS1 + $finalS2) / 2, 2)
        : null;
@endphp

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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

        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Academic History
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        Select a grade to view the student academic report.
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

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            @foreach([
                ['title' => 'Final 1st Semester', 'score' => $finalS1, 'formula' => ''],
                ['title' => 'Final 2nd Semester', 'score' => $finalS2, 'formula' => ''],
                ['title' => 'Final Score', 'score' => $finalScore, 'formula' => ''],
            ] as $card)
                @php
                    $grade = $getGradeLetter($card['score']);
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="px-6 py-4 border-b bg-purple-50">
                        <h3 class="font-bold text-gray-900">{{ $card['title'] }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $card['formula'] }}</p>
                    </div>

                    <div class="p-8 text-center">
                        @if($card['score'] !== null)
                            <h2 class="text-5xl font-bold text-gray-900">
                                {{ number_format($card['score'], 2) }}
                            </h2>

                            <span class="inline-flex mt-4 px-4 py-2 rounded-full bg-gray-100 text-sm font-bold">
                                Grade {{ $grade }}
                            </span>
                        @else
                            <p class="text-gray-500 font-semibold">
                                Waiting for complete scores
                            </p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- <div class="bg-white rounded-2xl shadow-sm border p-6 mb-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4">Formula Breakdown</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="border rounded-xl p-5 bg-gray-50">
                    <h3 class="font-bold text-gray-900 mb-3">Semester 1</h3>

                    <p class="text-sm text-gray-600">Monthly Average Dec → Mar</p>
                    <p class="text-2xl font-bold text-blue-700 mb-3">{{ $s1MonthlyAvg ?? '—' }}</p>

                    <p class="text-sm text-gray-600">S1 Exam Average</p>
                    <p class="text-2xl font-bold text-purple-700 mb-3">{{ $s1ExamAvg ?? '—' }}</p>

                    <p class="text-sm text-gray-600">Final Semester 1</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $finalS1 ?? '—' }}</p>
                </div>

                <div class="border rounded-xl p-5 bg-gray-50">
                    <h3 class="font-bold text-gray-900 mb-3">Semester 2</h3>

                    <p class="text-sm text-gray-600">Monthly Average May → Jul</p>
                    <p class="text-2xl font-bold text-blue-700 mb-3">{{ $s2MonthlyAvg ?? '—' }}</p>

                    <p class="text-sm text-gray-600">S2 Exam Average</p>
                    <p class="text-2xl font-bold text-purple-700 mb-3">{{ $s2ExamAvg ?? '—' }}</p>

                    <p class="text-sm text-gray-600">Final Semester 2</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $finalS2 ?? '—' }}</p>
                </div>
            </div>
        </div> --}}

        <h2 class="text-xl font-bold text-gray-900 mb-4">Monthly Scores</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            @foreach($months as $monthNum => $month)
                @php
                    $grades = $gradesByMonth[$monthNum] ?? [];
                    $hasData = count($grades) > 0;
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="{{ $hasData ? 'bg-blue-50' : 'bg-gray-50' }} px-6 py-4 border-b flex justify-between">
                        <h3 class="text-lg font-bold">{{ $month }}</h3>

                        @if(!$hasData)
                            <span class="px-3 py-1 rounded-full bg-yellow-50 border text-xs font-semibold text-yellow-700">
                                Awaiting input
                            </span>
                        @endif
                    </div>

                    @if($hasData)
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left">Subject</th>
                                    <th class="px-4 py-3 text-left">Teacher</th>
                                    <th class="px-4 py-3 text-center">Score</th>
                                    <th class="px-4 py-3 text-center">Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($grades as $grade)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">{{ $grade->subject_name ?? 'General Score' }}</td>
                                        <td class="px-4 py-3">
                                            {{ trim(($grade->first_name ?? '') . ' ' . ($grade->last_name ?? '')) ?: '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold">{{ $grade->score }}/100</td>
                                        <td class="px-4 py-3 text-center font-bold">{{ $grade->grade }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="px-6 py-4 bg-gray-50 border-t text-right">
                            <span class="text-sm text-gray-600">Monthly Average:</span>
                            <span class="font-bold text-gray-900">
                                {{ number_format(collect($grades)->avg('score'), 2) }}
                            </span>
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-500">
                            No scores for Grade {{ $selectedGrade }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-20">
            @foreach([
                ['title' => '1st Semester Exam Scores', 'scores' => $semester1Scores, 'avg' => $s1ExamAvg],
                ['title' => '2nd Semester Exam Scores', 'scores' => $semester2Scores, 'avg' => $s2ExamAvg],
            ] as $exam)
                <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                    <div class="bg-purple-50 px-6 py-4 border-b">
                        <h3 class="text-lg font-bold text-gray-900">{{ $exam['title'] }}</h3>
                    </div>

                    @if(collect($exam['scores'])->count())
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-4 py-3 text-left">Subject</th>
                                    <th class="px-4 py-3 text-left">Teacher</th>
                                    <th class="px-4 py-3 text-center">Score</th>
                                    <th class="px-4 py-3 text-center">Grade</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($exam['scores'] as $score)
                                    <tr class="border-b">
                                        <td class="px-4 py-3">{{ $score->subject_name ?? 'Subject' }}</td>
                                        <td class="px-4 py-3">
                                            {{ trim(($score->first_name ?? '') . ' ' . ($score->last_name ?? '')) ?: '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-center font-bold">{{ $score->score }}/100</td>
                                        <td class="px-4 py-3 text-center font-bold">{{ $score->grade }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="px-6 py-4 bg-gray-50 border-t text-right">
                            <span class="text-sm text-gray-600">Exam Average:</span>
                            <span class="font-bold text-gray-900">{{ number_format($exam['avg'], 2) }}</span>
                        </div>
                    @else
                        <div class="py-12 text-center text-gray-500">
                            No exam scores yet
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection
