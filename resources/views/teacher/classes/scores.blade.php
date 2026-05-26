@extends('layouts.teacher')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Score Management</h1>
        <p class="text-gray-600 mt-1">Enter monthly scores and semester exam scores.</p>
    </div>

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
               class="px-5 py-3 rounded-lg font-semibold text-center bg-blue-600 text-white">
                Scores
            </a>
        </div>
    </div>

    <form method="GET" id="filterForm" action="{{ route('teacher.classes.scores') }}"
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
                            {{ $class->grade_level }} - {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Score Type</label>
                <select name="report_type"
                        id="reportType"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="handleTypeChange()">
                    <option value="">Choose Type</option>
                    <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>
                        Monthly Score
                    </option>
                    <option value="semester" {{ request('report_type') == 'semester' ? 'selected' : '' }}>
                        Semester Exam
                    </option>
                </select>
            </div>

            <div id="monthDiv" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Month</label>
                <select name="month"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="document.getElementById('filterForm').submit()">
                    <option value="">Choose Month</option>

                    @php
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
                    @endphp

                    @foreach($months as $number => $name)
                        <option value="{{ $number }}" {{ request('month') == $number ? 'selected' : '' }}>
                            {{ $name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="semesterDiv" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Exam</label>
                <select name="semester"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full"
                        onchange="document.getElementById('filterForm').submit()">
                    <option value="">Choose Exam</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>2nd Semester</option>
                </select>
            </div>

        </div>

        <div class="flex flex-col md:flex-row gap-3 mt-5">
            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg">
                Load Students
            </button>

            <a href="{{ route('teacher.classes.scores') }}"
               class="bg-gray-400 hover:bg-gray-500 text-white font-semibold px-6 py-2.5 rounded-lg text-center">
                Clear
            </a>
        </div>
    </form>

    @if(request('class_id') && ((request('report_type') == 'monthly' && request('month')) || (request('report_type') == 'semester' && request('semester'))))

        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

            <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ request('report_type') == 'semester' ? 'Enter Semester Exam Scores' : 'Enter Monthly Scores' }}
                    </h2>

                    <p class="text-gray-600 text-sm mt-1">
                        Fill score from 0 to 100 for each student.
                    </p>
                </div>

                <input type="text"
                       id="studentSearch"
                       placeholder="Search student..."
                       class="border border-gray-300 rounded-lg px-4 py-2.5 w-full md:w-80">
            </div>

            <form method="POST" action="{{ route('teacher.classes.saveScores') }}">
                @csrf

                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                <input type="hidden" name="month" value="{{ request('month') ?? '' }}">
                <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                <input type="hidden" name="semester" value="{{ request('semester') ?? '' }}">

                <div class="overflow-x-auto">
                    <table class="min-w-full">

                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Student</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Student ID</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">Gender</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase">
                                    {{ request('report_type') == 'semester' ? 'Exam Score' : 'Monthly Score' }}
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($selectedClassStudents as $student)
                                <tr class="hover:bg-gray-50 score-row">

                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-gray-900">
                                            {{ $student->first_name }} {{ $student->last_name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ $student->student_id ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        {{ optional($student->user)->email ?? '—' }}
                                    </td>

                                    <td class="px-6 py-4 text-center text-sm text-gray-700">
                                        {{ ucfirst($student->gender ?? '—') }}
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        @if(request('report_type') == 'semester' && request('semester') == '1')
                                            <input type="number"
                                                   name="first_semester[{{ $student->id }}]"
                                                   value=""
                                                   placeholder="0"
                                                   min="0"
                                                   max="100"
                                                   step="0.01"
                                                   class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-center font-semibold">
                                        @elseif(request('report_type') == 'semester' && request('semester') == '2')
                                            <input type="number"
                                                   name="second_semester[{{ $student->id }}]"
                                                   value=""
                                                   placeholder="0"
                                                   min="0"
                                                   max="100"
                                                   step="0.01"
                                                   class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-center font-semibold">
                                        @else
                                            <input type="number"
                                                   name="final_score[{{ $student->id }}]"
                                                   value=""
                                                   placeholder="0"
                                                   min="0"
                                                   max="100"
                                                   step="0.01"
                                                   class="w-28 border border-gray-300 rounded-lg px-3 py-2 text-center font-semibold">
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                        No students found in this class.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                <div class="p-6 border-t border-gray-100 flex flex-col md:flex-row gap-3">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg">
                        Save Scores
                    </button>

                    <a href="{{ route('teacher.classes.scoresReport') }}"
                       class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 px-6 rounded-lg text-center">
                        View Score Report
                    </a>
                </div>

            </form>
        </div>

    @else
        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">
            <div class="text-gray-400 text-lg mb-1">No score list loaded</div>
            <p class="text-gray-500 text-sm">
                Please choose class, score type, and period first.
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
        } else if (type === 'semester') {
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
