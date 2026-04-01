@extends('layouts.student')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">
            My Classes
        </h2>

        <!-- Class Card -->
        @if(auth()->user()->student->class)
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4 text-white">
                    <h3 class="text-lg font-semibold">{{ auth()->user()->student->class->name }}</h3>
                    <p class="text-blue-100 text-sm">Grade {{ auth()->user()->student->class->grade_level }}</p>
                </div>

                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <p class="text-sm text-gray-600">Class Teacher</p>
                            <p class="font-semibold text-gray-900">
                                {{ auth()->user()->student->class->teacher
                                    ? auth()->user()->student->class->teacher->first_name . ' ' . auth()->user()->student->class->teacher->last_name
                                    : 'Not Assigned' }}
                            </p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <p class="text-sm text-gray-600">Total Students</p>
                            <p class="font-semibold text-gray-900">{{ auth()->user()->student->class->students->count() }}</p>
                        </div>
                        <div class="border-l-4 border-purple-500 pl-4">
                            <p class="text-sm text-gray-600">Subject</p>
                            <p class="font-semibold text-gray-900">
                                {{ auth()->user()->student->class->teacher && auth()->user()->student->class->teacher->subject
                                    ? auth()->user()->student->class->teacher->subject->name
                                    : 'N/A' }}
                            </p>
                        </div>
                    </div>

                    <h4 class="font-semibold text-gray-900 mb-3">Class Roster</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Student Name</th>
                                    <th class="px-4 py-2 text-left text-sm font-semibold text-gray-700">Student ID</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach(auth()->user()->student->class->students as $classmate)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 text-sm text-gray-800">{{ $classmate->first_name }} {{ $classmate->last_name }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-600">{{ $classmate->student_id ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Not Assigned to a Class</h3>
                <p class="text-gray-500">You haven't been assigned to any class yet. Contact your administrator.</p>
            </div>
        @endif
    </div>
</div>
@endsection
