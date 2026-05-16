@extends('layouts.teacher')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">

        <div class="flex items-center gap-4">
            <a href="{{ route('teacher.classes.index') }}"
               class="inline-flex items-center text-gray-600 hover:text-blue-600 font-medium">
                ← Back
            </a>

            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                    {{ $class->name }}
                </h1>

                <p class="text-gray-600 mt-1">
                    Grade Level: {{ $class->grade_level ?? '—' }}
                </p>
            </div>
        </div>

        <div>
            <span class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                {{ $class->students->count() }} Student(s)
            </span>
        </div>

    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

        <div class="p-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-900">
                Student List
            </h2>

            <p class="text-gray-600 text-sm mt-1">
                All students currently assigned to this class.
            </p>
        </div>

        @if($class->students && $class->students->count())

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

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Email
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Age
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Gender
                            </th>

                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @foreach ($class->students as $student)

                            <tr class="hover:bg-gray-50 transition">

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

                                <!-- Email -->
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ optional($student->user)->email ?? '—' }}
                                </td>

                                <!-- Age -->
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($student->date_of_birth)
                                        {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <!-- Gender -->
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ ucfirst($student->gender ?? '—') }}
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-center">

                                    <a href="{{ route('teacher.students.show', $student->id) }}"
                                       class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold">
                                        View
                                    </a>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

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

    </div>

</div>
@endsection
