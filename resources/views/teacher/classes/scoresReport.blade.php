@extends('layouts.teacher')

@section('content')
@php
    $months = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];

    $selectedClass = $classes->firstWhere('id', request('class_id'));

    $reportTitle = 'Scores Report';

    if (request('report_type') == 'monthly' && request('month')) {
        $reportTitle = ($months[(int) request('month')] ?? 'Monthly') . ' Scores';
    } elseif (request('report_type') == 'semester' && request('semester') == '1') {
        $reportTitle = '1st Semester Scores';
    } elseif (request('report_type') == 'semester' && request('semester') == '2') {
        $reportTitle = '2nd Semester Scores';
    } elseif (request('report_type') == 'final') {
        $reportTitle = 'Final Scores';
    }
@endphp

<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Scores Report</h1>
        <p class="text-gray-600 mt-1">View and analyze student scores by class and period.</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-xl shadow border border-gray-100 p-2 mb-6">
        <div class="flex flex-col md:flex-row gap-2">
            <a href="{{ route('teacher.classes.index') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Classes
            </a>

            <a href="{{ route('teacher.classes.attend') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Attendance
            </a>

            <a href="{{ route('teacher.classes.scores') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Scores
            </a>

            <a href="{{ route('teacher.classes.scoresReport') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center bg-blue-600 text-white">
                Report
            </a>
        </div>
    </div>

    <!-- Filter -->
    <form method="GET" id="filterForm" action="{{ route('teacher.classes.scoresReport') }}"
          class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Class</label>
                <select name="class_id"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="document.getElementById('filterForm').submit()">
                    <option value="">Choose Class</option>

                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            Grade {{ $class->grade_level }} - {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Report Type</label>
                <select name="report_type"
                        id="reportType"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="handleTypeChange()">
                    <option value="">Choose Type</option>
                    <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    <option value="semester" {{ request('report_type') == 'semester' ? 'selected' : '' }}>Semester</option>
                    <option value="final" {{ request('report_type') == 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>

            <div id="monthDiv" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                <select name="month"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="document.getElementById('filterForm').submit()">
                    <option value="">Choose Month</option>

                    @foreach($months as $number => $name)
                        <option value="{{ $number }}" {{ request('month') == $number ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="semesterDiv" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Period</label>
                <select name="semester"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="document.getElementById('filterForm').submit()">
                    <option value="">Choose Period</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
                    <option value="final" {{ request('semester') == 'final' ? 'selected' : '' }}>Final</option>
                </select>
            </div>

        </div>

        <div class="flex flex-col md:flex-row gap-3 mt-5">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg">
                Load Report
            </button>

            <a href="{{ route('teacher.classes.scoresReport') }}"
               class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-2.5 rounded-lg text-center">
                Clear
            </a>
        </div>
    </form>

    @if(request('class_id') && ((request('report_type') == 'monthly' && request('month')) || (request('report_type') == 'semester' && request('semester')) || (request('report_type') == 'final' && request('semester'))))

        <!-- Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div class="bg-white rounded-xl shadow border border-gray-100 p-5">
                <div class="text-gray-500 text-sm">Report</div>
                <div class="mt-1 text-xl font-bold text-gray-900">{{ $reportTitle }}</div>
            </div>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-5">
                <div class="text-gray-500 text-sm">Class</div>
                <div class="mt-1 text-xl font-bold text-blue-700">
                    {{ $selectedClass ? 'Grade ' . $selectedClass->grade_level . ' - ' . $selectedClass->name : '—' }}
                </div>
            </div>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-5">
                <div class="text-gray-500 text-sm">Total Records</div>
                <div class="mt-1 text-xl font-bold text-green-700">
                    {{ method_exists($scoreRecords, 'total') ? $scoreRecords->total() : $scoreRecords->count() }}
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

            <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">{{ $reportTitle }}</h2>
                    <p class="text-gray-600 text-sm mt-1">Student score records for the selected period.</p>
                </div>

                <input type="text"
                       id="studentSearch"
                       placeholder="Search student..."
                       class="border border-gray-300 rounded-lg px-4 py-2.5 w-full md:w-80">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full">

                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Student</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Student ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Gender</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Score</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse($scoreRecords as $record)
                            @php
                                if(request('report_type') == 'semester' && request('semester') == '1') {
                                    $scoreValue = $record->first_semester;
                                } elseif(request('report_type') == 'semester' && request('semester') == '2') {
                                    $scoreValue = $record->second_semester;
                                } else {
                                    $scoreValue = $record->final_score;
                                }

                                if ($scoreValue === null) {
                                    $scoreColor = 'bg-gray-100 text-gray-700';
                                } elseif ($scoreValue >= 90) {
                                    $scoreColor = 'bg-green-100 text-green-800';
                                } elseif ($scoreValue >= 80) {
                                    $scoreColor = 'bg-blue-100 text-blue-800';
                                } elseif ($scoreValue >= 70) {
                                    $scoreColor = 'bg-yellow-100 text-yellow-800';
                                } elseif ($scoreValue >= 60) {
                                    $scoreColor = 'bg-orange-100 text-orange-800';
                                } else {
                                    $scoreColor = 'bg-red-100 text-red-800';
                                }
                            @endphp

                            <tr class="hover:bg-gray-50 score-row">

                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-900">
                                        {{ $record->student->user->name ?? 'N/A' }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $record->student->student_id ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $record->student->user->email ?? '—' }}
                                </td>

                                <td class="px-6 py-4 text-center text-sm text-gray-700">
                                    {{ ucfirst($record->student->gender ?? '—') }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex justify-center min-w-[70px] px-3 py-1.5 rounded-full text-sm font-bold {{ $scoreColor }}">
                                        {{ $scoreValue ?? '—' }}
                                    </span>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    No score records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @if($scoreRecords->count() > 0)
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $scoreRecords->links() }}
                </div>
            @endif
        </div>

    @elseif(request('class_id'))
        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">
            <div class="text-gray-400 text-lg mb-1">No report loaded</div>
            <p class="text-gray-500 text-sm">
                Please choose report type and period first.
            </p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">
            <div class="text-gray-400 text-lg mb-1">No class selected</div>
            <p class="text-gray-500 text-sm">
                Please choose a class to view the score report.
            </p>
        </div>
    @endif

</div>

<script>
    function updatePeriodFields() {
        const type = document.getElementById('reportType')?.value;
        const monthDiv = document.getElementById('monthDiv');
        const semesterDiv = document.getElementById('semesterDiv');

        if (type === 'monthly') {
            monthDiv.classList.remove('hidden');
            semesterDiv.classList.add('hidden');
        } else if (type === 'semester' || type === 'final') {
            semesterDiv.classList.remove('hidden');
            monthDiv.classList.add('hidden');
        } else {
            monthDiv.classList.add('hidden');
            semesterDiv.classList.add('hidden');
        }
    }

    function handleTypeChange() {
        updatePeriodFields();

        const type = document.getElementById('reportType')?.value;

        if (type === '') {
            document.getElementById('filterForm').submit();
        }
    }

    updatePeriodFields();

    const studentSearch = document.getElementById('studentSearch');

    studentSearch?.addEventListener('keyup', function () {
        const value = this.value.toLowerCase();

        document.querySelectorAll('.score-row').forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(value) ? '' : 'none';
        });
    });
</script>
@endsection
