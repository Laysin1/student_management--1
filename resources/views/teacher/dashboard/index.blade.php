@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">
            Teacher Dashboard
        </h2>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">My Classes</div>
                <div class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $myClasses ?? 0 }}
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">My Students</div>
                <div class="mt-2 text-2xl font-bold text-green-700">
                    {{ $myStudents ?? 0 }}
                </div>
            </div>
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">My Schedules</div>
                <div class="mt-2 text-2xl font-bold text-yellow-700">
                    {{ $mySchedules ?? 0 }}
                </div>
            </div>

        </div>

        <!-- Welcome Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">Welcome, {{ auth()->user()->name ?? 'Teacher' }}!</h3>
            <p class="text-gray-600">Here is your dashboard overview.</p>
        </div>

        <!-- My Classes and Students -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="font-semibold text-xl text-gray-800 mb-4">My Classes & Students</h3>

            @if(isset($classes) && $classes->count())
                @foreach($classes as $class)
                    <div class="mb-6 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-semibold text-lg text-blue-700">
                                {{ $class->name }} ({{ $class->grade_level }})
                            </h4>
                            <span class="text-sm text-gray-500">
                                {{ $class->students->count() }} student(s)
                            </span>
                        </div>

                        @if($class->students && $class->students->count())
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-gray-700">Student ID</th>
                                            <th class="px-4 py-2 text-left text-gray-700">Name</th>
                                            <th class="px-4 py-2 text-left text-gray-700">Email</th>
                                            <th class="px-4 py-2 text-left text-gray-700">Gender</th>
                                            <th class="px-4 py-2 text-left text-gray-700">Age</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($class->students as $student)
                                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                                <td class="px-4 py-2 text-gray-800">{{ $student->student_id ?? '—' }}</td>
                                                <td class="px-4 py-2 text-gray-800">{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td class="px-4 py-2 text-gray-600">{{ optional($student->user)->email ?? '—' }}</td>
                                                <td class="px-4 py-2 text-gray-600">{{ $student->gender ?? '—' }}</td>
                                                <td class="px-4 py-2 text-gray-600">{{ $student->age ?? '—' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No students in this class.</p>
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-gray-500">You don't have any classes assigned yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
