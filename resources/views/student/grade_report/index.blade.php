@extends('layouts.student')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header with Year Selector -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Grade Report</h1>
                <p class="text-sm text-gray-600">Student ID: {{ $student->student_id ?? 'N/A' }} | Total Grades: {{ collect($gradesByMonth)->sum(fn($m) => count($m)) }}</p>
            </div>
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700">Year:</label>
                <select id="yearSelect" class="px-6 py-2 w-32 border border-gray-300 rounded-lg bg-white text-gray-900 font-medium hover:border-gray-400 focus:outline-none focus:border-blue-500">
                    <option value="2026" @if(request()->query('year') == '2026' || !request()->query('year')) selected @endif>2026</option>
                    <option value="2025" @if(request()->query('year') == '2025') selected @endif>2025</option>
                    <option value="2024" @if(request()->query('year') == '2024') selected @endif>2024</option>
                    <option value="2023" @if(request()->query('year') == '2023') selected @endif>2023</option>
                    <option value="2022" @if(request()->query('year') == '2022') selected @endif>2022</option>
                    <option value="2021" @if(request()->query('year') == '2021') selected @endif>2021</option>
                    <option value="2020" @if(request()->query('year') == '2020') selected @endif>2020</option>
                </select>
            </div>
        </div>

        <!-- DEBUG INFO -->
        {{-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <p class="text-sm font-bold text-blue-900">🔍 DEBUG INFO:</p>
            <p class="text-sm text-blue-800">Student Model ID: <strong>{{ $student->id ?? 'NULL' }}</strong></p>
            <p class="text-sm text-blue-800">Student Number: <strong>{{ $student->student_id ?? 'NULL' }}</strong></p>
            <p class="text-sm text-blue-800">Total Grades Returned: <strong>{{ collect($gradesByMonth)->sum(fn($m) => count($m)) }}</strong></p>
            <p class="text-sm text-blue-800">Months with Data:
                <strong>
                    @php
                        $monthsWithData = [];
                        foreach($gradesByMonth as $monthNum => $grades) {
                            if(count($grades) > 0) $monthsWithData[] = $monthNum;
                        }
                        echo count($monthsWithData) > 0 ? implode(', ', $monthsWithData) : 'NONE';
                    @endphp
                </strong>
            </p>
            <p class="text-xs text-blue-700 mt-2">Raw: {{ json_encode($gradesByMonth) }}</p>
        </div> --}}

        <!-- Monthly Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-20">
            @php
                $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $monthNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
            @endphp

            @foreach($months as $index => $month)
                @php
                    $monthNum = $monthNumbers[$index];
                    $grades = $gradesByMonth[$monthNum] ?? [];
                    $hasData = count($grades) > 0;
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="@if($hasData) bg-blue-100 @else bg-gray-100 @endif px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">{{ $month }}</h3>
                        @if(!$hasData)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-50 border border-yellow-200">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-medium text-yellow-700">Awaiting input</span>
                            </span>
                        @endif
                    </div>

                    @if($hasData)
                        <!-- Data Table -->
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
                                            <td class="px-4 py-3 text-gray-900 font-medium">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-gray-900">
                                                {{ $grade->subject_name ?? 'General Score' }}
                                            </td>
                                            <td class="px-4 py-3 text-gray-900">
                                                {{ ($grade->first_name ?? '') . ' ' . ($grade->last_name ?? '') ?: '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-900 font-medium">{{ $grade->score }}/{{ $grade->total_score ?? 100 }}</td>
                                            <td class="px-4 py-3 text-center font-semibold @if($grade->grade === 'A') text-green-600 @elseif($grade->grade === 'B') text-blue-600 @elseif($grade->grade === 'C') text-yellow-600 @elseif($grade->grade === 'D') text-orange-600 @else text-red-600 @endif">
                                                {{ $grade->grade_status ?? $grade->grade }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Total Attendance</p>
                                <p class="text-lg font-bold text-gray-900">{{ $attendanceByMonth[$monthNum] ?? '0/0' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600">Average Score</p>
                                <p class="text-lg font-bold text-gray-900">
                                    @php
                                        $avgScore = count($grades) > 0 ? number_format(collect($grades)->avg('score'), 2) : '0.00';
                                    @endphp
                                    {{ $avgScore }}
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center py-16">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-bold text-gray-900 mb-1">No data available</p>
                            <p class="text-sm text-gray-500">Grades will appear when submitted</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Semester Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20">
            @php
                $semesters = [
                    'first_semester' => 'Semester 1',
                    'second_semester' => 'Semester 2',
                    'final_score' => 'Final Score'
                ];
            @endphp

            @foreach($semesters as $key => $label)
                @php
                    $semesterScores = DB::table('scores')
                        ->where('student_id', $student->id)
                        ->where('year', request()->query('year', 2026))
                        ->whereNotNull($key)
                        ->where($key, '>', 0)
                        ->where(function($q) {
                            $q->whereNull('month')->orWhere('month', 0);
                        })
                        ->get();

                    $hasData = count($semesterScores) > 0;
                @endphp

                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="@if($hasData) bg-purple-100 @else bg-gray-100 @endif px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-900">{{ $label }}</h3>
                        @if(!$hasData)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-50 border border-yellow-200">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-xs font-medium text-yellow-700">Awaiting input</span>
                            </span>
                        @endif
                    </div>

                    @if($hasData)
                        <!-- Data Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">No.</th>
                                        <th class="px-4 py-3 text-left font-semibold text-gray-700">Month</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Score</th>
                                        <th class="px-4 py-3 text-center font-semibold text-gray-700">Grade</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($semesterScores as $index => $score)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-3 text-gray-900 font-medium">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 text-gray-900">
                                                @php
                                                    $monthNames = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                                                    echo $monthNames[$score->month] ?? 'N/A';
                                                @endphp
                                            </td>
                                            <td class="px-4 py-3 text-center text-gray-900 font-medium">{{ $score->{$key} }}/100</td>
                                            <td class="px-4 py-3 text-center font-semibold @if($score->grade === 'A') text-green-600 @elseif($score->grade === 'B') text-blue-600 @elseif($score->grade === 'C') text-yellow-600 @elseif($score->grade === 'D') text-orange-600 @else text-red-600 @endif">
                                                {{ $score->grade }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                            <div class="text-right">
                                <p class="text-gray-600 text-sm">Average Score</p>
                                <p class="text-lg font-bold text-gray-900">
                                    @php
                                        $avgScore = collect($semesterScores)->avg($key);
                                        echo number_format($avgScore, 2);
                                    @endphp
                                </p>
                            </div>
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="flex flex-col items-center justify-center py-16">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg font-bold text-gray-900 mb-1">No data available</p>
                            <p class="text-sm text-gray-500">Grades will appear when submitted</p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="fixed bottom-0 left-64 right-0 bg-white border-t-2 border-blue-500 px-6 py-3">
            <div class="max-w-7xl mx-auto flex items-center gap-3">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-gray-700">Semester is coming soon</p>
            </div>
        </div>
    </div>
</div>

<!-- Year Selector Script -->
<script>
    document.getElementById('yearSelect').addEventListener('change', function() {
        const year = this.value;
        window.location.href = `{{ route('student.scores') }}?year=${year}`;
    });
</script>

@endsection
