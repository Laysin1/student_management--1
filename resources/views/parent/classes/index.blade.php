@extends('layouts.parent')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white mb-8 shadow">
            <h1 class="text-3xl font-bold">My Children</h1>

            <p class="mt-0 text-blue-100">
                View your children’s information, grades, and attendance records.
            </p>
        </div>

        <!-- Stats -->
        {{-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            <!-- Total Children -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">Total Children</p>

                <h2 class="mt-2 text-3xl font-bold text-blue-600">
                    {{ count($students) }}
                </h2>

                <p class="mt-2 text-xs text-gray-400">
                    Linked to your account
                </p>
            </div>

            <!-- Total Classes -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">Classes</p>

                <h2 class="mt-2 text-3xl font-bold text-purple-600">
                    {{ collect($students)->pluck('class_id')->filter()->unique()->count() }}
                </h2>

                <p class="mt-2 text-xs text-gray-400">
                    Different classes
                </p>
            </div>

            <!-- School Year -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <p class="text-sm text-gray-500">School Year</p>

                <h2 class="mt-2 text-3xl font-bold text-green-600">
                    2026
                </h2>

                <p class="mt-2 text-xs text-gray-400">
                    Current academic year
                </p>
            </div>

        </div> --}}

        <!-- Children Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($students as $student)

                @php
                    $class = DB::table('school_classes')
                        ->where('id', $student->class_id)
                        ->first();

                    $studentAvg = DB::table('scores')
                        ->where('student_id', $student->id)
                        ->where('year', 2026)
                        ->whereNotNull('final_score')
                        ->avg('final_score');

                    $presentCount = DB::table('attendances')
                        ->where('student_id', $student->id)
                        ->where('status', 'present')
                        ->count();

                    $attendanceCount = DB::table('attendances')
                        ->where('student_id', $student->id)
                        ->count();

                    $attendanceRate = $attendanceCount > 0
                        ? ($presentCount / $attendanceCount) * 100
                        : 0;
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition duration-300">

                    <!-- Top Banner -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-24 relative">

                        <!-- Avatar -->
                        <div class="absolute -bottom-10 left-6">
                            <div class="w-20 h-20 rounded-full bg-white border-4 border-white shadow flex items-center justify-center text-2xl font-bold text-blue-700">
                                {{ strtoupper(substr($student->first_name, 0, 1)) }}
                            </div>
                        </div>

                    </div>

                    <!-- Content -->
                    <div class="pt-14 p-6">

                        <!-- Student Info -->
                        <div class="mb-5">
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $student->first_name }} {{ $student->last_name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Student ID: {{ $student->student_id }}
                            </p>
                        </div>

                        <!-- Information -->
                        <div class="space-y-3 mb-6">

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Class</span>

                                <span class="font-semibold text-gray-800">
                                    {{ $class->name ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Grade</span>

                                <span class="font-semibold text-gray-800">
                                    {{ $class->grade_level ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Gender</span>

                                <span class="font-semibold text-gray-800">
                                    {{ $student->gender ?? 'N/A' }}
                                </span>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="text-gray-500 text-sm">Age</span>

                                <span class="font-semibold text-gray-800">
                                    @if(!empty($student->date_of_birth))
    {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}
@elseif(!empty($student->dob))
    {{ \Carbon\Carbon::parse($student->dob)->age }}
@else
    N/A
@endif
                                </span>
                            </div>

                        </div>

                        <!-- Academic Stats -->
                        <div class="grid grid-cols-2 gap-4 mb-6">

                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-green-600 font-medium">
                                    Average Grade
                                </p>

                                <h4 class="text-xl font-bold text-green-700 mt-1">
                                    {{ number_format($studentAvg ?? 0, 1) }}
                                </h4>
                            </div>

                            <div class="bg-yellow-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-yellow-600 font-medium">
                                    Attendance
                                </p>

                                <h4 class="text-xl font-bold text-yellow-700 mt-1">
                                    {{ number_format($attendanceRate, 1) }}%
                                </h4>
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="grid grid-cols-2 gap-3">

                            <a href="{{ route('parent.grades', $student->id) }}"
                               class="text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition duration-300">

                                View Grades

                            </a>

                            <a href="/parent/attendance/{{ $student->id }}"
                               class="text-center bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl transition duration-300">

                                Attendance

                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <!-- Empty State -->
                <div class="col-span-full">

                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center">

                        <h3 class="text-2xl font-bold text-gray-800">
                            No Children Linked
                        </h3>

                        <p class="text-gray-500 mt-2">
                            Please contact the school administrator to link your children to this account.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

    </div>
</div>
@endsection
