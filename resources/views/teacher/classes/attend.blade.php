@extends('layouts.teacher')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Attendance Management
        </h1>

        <p class="text-gray-600 mt-1">
            Manage daily student attendance records.
        </p>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-white rounded-xl shadow border border-gray-100 p-2 mb-6">
        <div class="flex flex-col md:flex-row gap-2">

            <a href="{{ route('teacher.classes.index') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Classes
            </a>

            <a href="{{ route('teacher.classes.attend') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center bg-blue-600 text-white">
                Attendance
            </a>

            <a href="{{ route('teacher.classes.scores') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Scores
            </a>

        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <!-- Class -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Select Class
                </label>

                <form method="GET" id="classForm">
                    <select name="class_id"
                            class="border border-gray-300 rounded-lg px-4 py-2.5 w-full focus:ring-2 focus:ring-blue-500"
                            onchange="document.getElementById('classForm').submit()">

                        <option value="">-- Choose a Class --</option>

                        @foreach($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                Grade {{ $class->grade_level }} - {{ $class->name }}
                            </option>
                        @endforeach

                    </select>
                </form>
            </div>

            <!-- Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Attendance Date
                </label>

                <input type="date"
                       id="attendanceDate"
                       value="{{ request('date', date('Y-m-d')) }}"
                       class="border border-gray-300 rounded-lg px-4 py-2.5 w-full focus:ring-2 focus:ring-blue-500">
            </div>

        </div>

    </div>

    @if(request('class_id'))

        <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

            <!-- Header + Search -->
            <div class="p-5 border-b border-gray-100">

                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    <div>
                        <h2 class="text-lg font-bold text-gray-900">
                            Student Attendance
                        </h2>

                        <p class="text-gray-600 text-sm mt-1">
                            Mark attendance for all students in the selected class.
                        </p>
                    </div>

                    <!-- Search -->
                    <div class="w-full md:w-80">
                        <input type="text"
                               id="studentSearch"
                               placeholder="Search student..."
                               class="border border-gray-300 rounded-lg px-4 py-2.5 w-full focus:ring-2 focus:ring-blue-500">
                    </div>

                </div>

            </div>

            <form method="POST" action="{{ route('teacher.classes.saveAttend') }}">
                @csrf

                <input type="hidden" name="class_id" value="{{ request('class_id') }}">

                <input type="hidden"
                       name="attendance_date"
                       onchange="submitFilter()"
                       id="attendanceDateInput"
                       value="{{ request('date', date('Y-m-d')) }}">

                @if($selectedClassStudents->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full">

                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Student
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Student ID
                                    </th>

                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Present
                                    </th>

                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Absent
                                    </th>

                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Permission
                                    </th>

                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                        Remarks
                                    </th>

                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100">

                                @foreach($selectedClassStudents as $student)

                                    <tr class="hover:bg-gray-50 transition student-row">

                                        <!-- Student -->
                                        <td class="px-6 py-4">

                                            <div class="flex items-center gap-3">

                                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold">
                                                    {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                                </div>

                                                <div>
                                                    <div class="font-semibold text-gray-900">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </div>

                                                    <div class="text-sm text-gray-500">
                                                        Student
                                                    </div>
                                                </div>

                                            </div>

                                        </td>

                                        <!-- Student ID -->
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $student->student_id ?? '—' }}
                                        </td>

                                        <!-- Present -->
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio"
                                                   name="attendance[{{ $student->id }}]"
                                                   value="present"
                                                   class="w-4 h-4 text-green-600">
                                        </td>

                                        <!-- Absent -->
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio"
                                                   name="attendance[{{ $student->id }}]"
                                                   value="absent"
                                                   class="w-4 h-4 text-red-600">
                                        </td>

                                        <!-- Permission -->
                                        <td class="px-6 py-4 text-center">
                                            <input type="radio"
                                                   name="attendance[{{ $student->id }}]"
                                                   value="permission"
                                                   class="w-4 h-4 text-yellow-500">
                                        </td>

                                        <!-- Remarks -->
                                        <td class="px-6 py-4">
                                            <input type="text"
                                                   name="remarks[{{ $student->id }}]"
                                                   placeholder="Optional remarks"
                                                   class="border border-gray-300 rounded-lg px-3 py-2 w-full text-sm">
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="p-6 border-t border-gray-100 flex flex-col md:flex-row gap-3">

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg">
                            Save Attendance
                        </button>

                        <a href="{{ route('teacher.classes.attendanceReport') }}"
                           class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2.5 px-6 rounded-lg text-center">
                            View Daily Report
                        </a>

                    </div>

                @else

                    <div class="p-10 text-center">

                        <div class="text-gray-400 text-lg mb-1">
                            No students found
                        </div>

                        <p class="text-gray-500 text-sm">
                            There are currently no students assigned to this class.
                        </p>

                    </div>

                @endif

            </form>

        </div>

    @else

        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">

            <div class="text-gray-400 text-lg mb-1">
                No class selected
            </div>

            <p class="text-gray-500 text-sm">
                Please choose a class to manage attendance.
            </p>

        </div>

    @endif

</div>

<script>
    document.getElementById('attendanceDate')?.addEventListener('change', function () {
        document.getElementById('attendanceDateInput').value = this.value;
    });

    const studentSearch = document.getElementById('studentSearch');

    studentSearch?.addEventListener('keyup', function () {

        const value = this.value.toLowerCase();

        document.querySelectorAll('.student-row').forEach(row => {

            const text = row.innerText.toLowerCase();

            if (text.includes(value)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }

        });

    });
</script>
@endsection
