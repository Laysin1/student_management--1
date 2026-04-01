@extends('layouts.student')

@section('content')
<div class="py-8 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="font-bold text-4xl text-gray-900 mb-2">Welcome, {{ auth()->user()->first_name }}! 👋</h2>
            <p class="text-gray-600 text-lg">Here's your academic dashboard</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Class Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">My Class</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">
                            {{ $student->class ? $student->class->name : 'N/A' }}
                        </p>
                        @if($student->class)
                            <p class="text-xs text-gray-500 mt-1"> {{ $student->class->grade_level }}</p>
                        @endif
                    </div>
                    <svg class="w-12 h-12 text-blue-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                </div>
            </div>

            <!-- Class Strength Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Class Member</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            {{ $student->class ? $student->class->students->count() : 0 }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">students</p>
                    </div>
                    <svg class="w-12 h-12 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 10a1 1 0 100-2 1 1 0 000 2zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14 10a1 1 0 100-2 1 1 0 000 2zM14 14a4 4 0 00-8 0v2h8v-2zM9 15a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                </div>
            </div>

            <!-- Teacher Card -->
            {{-- <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Class Teacher</p>
                        <p class="text-lg font-bold text-purple-600 mt-2">
                            {{ $student->class && $student->class->teacher
                                ? $student->class->teacher->first_name . ' ' . $student->class->teacher->last_name
                                : 'Not Assigned' }}
                        </p>
                        @if($student->class && $student->class->teacher && $student->class->teacher->subject)
                            <p class="text-xs text-gray-500 mt-1">{{ $student->class->teacher->subject->name }}</p>
                        @endif
                    </div>
                    <svg class="w-12 h-12 text-purple-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div> --}}

            <!-- Student ID Card -->
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm">Student ID</p>
                        <p class="text-xl font-bold text-indigo-600 mt-2">
                            {{ $student->student_id ?? 'N/A' }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">ID #{{ $student->id }}</p>
                    </div>
                    <svg class="w-12 h-12 text-indigo-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Personal Information Section -->
        <div class="grid grid-cols-1 gap-6 mb-8">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <p class="text-xs text-gray-600">Full Name</p>
                        <p class="font-semibold text-gray-900">{{ $student->first_name }} {{ $student->last_name }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <p class="text-xs text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <p class="text-xs text-gray-600">Date of Birth</p>
                        <p class="font-semibold text-gray-900">
                            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'N/A' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <p class="text-xs text-gray-600">Gender</p>
                        <p class="font-semibold text-gray-900">{{ $student->gender ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <p class="text-xs text-gray-600">Phone Number</p>
                        <p class="font-semibold text-gray-900">{{ $student->phone_number ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded border border-gray-200">
                        <p class="text-xs text-gray-600">Parent Number</p>
                        <p class="font-semibold text-gray-900">{{ $student->parent_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 17v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/>
                    </svg>
                    Quick Links
                </h3>
                <div class="space-y-2">
                    {{-- <a href="{{ route('student.classes.index') }}" class="block px-4 py-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg font-semibold transition">
                        📚 My Classes
                    </a> --}}
                    <a href="{{ route('student.attendance') }}" class="block px-4 py-3 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg font-semibold transition">
                        ✅ Attendance
                    </a>
                    <a href="{{ route('student.scores') }}" class="block px-4 py-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg font-semibold transition">
                        📊 My Scores
                    </a>
                    <a href="{{ route('student.setting.index') }}" class="block px-4 py-3 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 rounded-lg font-semibold transition">
                        ⚙️ Settings
                    </a>
                </div>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.951 1.488a1 1 0 001.169-1.409l-7-14z"/>
                </svg>
                Announcements
            </h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-blue-900 font-semibold mb-1">📢 Welcome to your Dashboard</p>
                <p class="text-blue-800 text-sm">Navigate through your classes, check your attendance, view scores, and update your profile settings.</p>
            </div>
        </div>
    </div>
</div>
@endsection
