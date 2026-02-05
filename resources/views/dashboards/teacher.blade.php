{{-- filepath: resources/views/dashboards/teacher.blade.php --}}
@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">
            Teacher Dashboard
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Example Block 1 -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">My Classes</div>
                <div class="mt-2 text-2xl font-bold text-blue-700">
                    {{ $myClasses ?? 0 }}
                </div>
            </div>
            <!-- Example Block 2 -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">My Students</div>
                <div class="mt-2 text-2xl font-bold text-green-700">
                    {{ $myStudents ?? 0 }}
                </div>
            </div>
            <!-- Example Block 3 -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">My Schedules</div>
                <div class="mt-2 text-2xl font-bold text-yellow-700">
                    {{ $mySchedules ?? 0 }}
                </div>
            </div>
            <!-- Example Block 4 -->
            <div class="bg-white shadow rounded-lg p-6 flex flex-col items-start">
                <div class="text-gray-500">Messages</div>
                <div class="mt-2 text-2xl font-bold text-red-700">
                    {{ $myMessages ?? 0 }}
                </div>
            </div>
        </div>
        <!-- You can add more dashboard content here -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="font-semibold text-lg text-gray-800 mb-2">Welcome, Teacher!</h3>
            <p class="text-gray-600">Here is your dashboard overview.</p>
        </div>
    </div>
</div>
@endsection
