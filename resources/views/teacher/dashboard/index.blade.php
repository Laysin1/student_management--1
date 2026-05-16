@extends('layouts.teacher')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Teacher Dashboard
        </h1>
        <p class="text-gray-600 mt-1">
            Welcome back, {{ auth()->user()->name ?? 'Teacher' }}. Here is your teaching overview.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <p class="text-gray-500 text-sm font-semibold">My Classes</p>
            <h2 class="text-3xl font-bold text-blue-700 mt-2">{{ $myClasses ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <p class="text-gray-500 text-sm font-semibold">My Students</p>
            <h2 class="text-3xl font-bold text-green-700 mt-2">{{ $myStudents ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
            <p class="text-gray-500 text-sm font-semibold">My Schedules</p>
            <h2 class="text-3xl font-bold text-yellow-700 mt-2">{{ $mySchedules ?? 0 }}</h2>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">
            My Classes & Students
        </h3>
        <p class="text-gray-600 text-sm">
            View all classes assigned to you and the students inside each class.
        </p>
    </div>

    @if(isset($classes) && $classes->count())
        <div class="space-y-6">
            @foreach($classes as $class)
                <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

                    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $class->name }}
                            </h3>

                            <p class="text-gray-600 text-sm mt-1">
                                Grade Level: {{ $class->grade_level ?? '—' }}
                            </p>
                        </div>

                        <span class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                            {{ $class->students->count() }} Student(s)
                        </span>
                    </div>

                    @if($class->students && $class->students->count())
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Student ID
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Email
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Gender
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Age
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-gray-100">
                                    @foreach($class->students as $student)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 text-sm text-gray-800">
                                                {{ $student->student_id ?? '—' }}
                                            </td>

                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold">
                                                        {{ strtoupper(substr($student->first_name, 0, 1)) }}
                                                    </div>

                                                    <div class="font-semibold text-gray-900">
                                                        {{ $student->first_name }} {{ $student->last_name }}
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                {{ optional($student->user)->email ?? '—' }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                {{ $student->gender ?? '—' }}
                                            </td>

                                            <td class="px-6 py-4 text-sm text-gray-700">
                                                @if($student->date_of_birth)
                                                    {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-gray-500 text-sm">
                            No students in this class.
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">
            <div class="text-gray-400 text-lg mb-1">
                No classes assigned yet
            </div>
            <p class="text-gray-500 text-sm">
                Once the admin assigns classes to you, they will appear here.
            </p>
        </div>
    @endif

</div>
@endsection
