@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <p class="mt-2 text-blue-100">
            Welcome to the RUPP Admin Panel. Manage teachers, classes, students, and schedules.
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <!-- Teachers -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Teachers</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalTeachers }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l6.16-3.422a12.083 12.083 0 01.84 4.42c0 3.31-2.69 6-6 6s-6-2.69-6-6a12.083 12.083 0 01.84-4.42L12 14z" />
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.teachers.index') }}"
               class="inline-block mt-5 text-sm font-semibold text-blue-600 hover:text-blue-800">
                Manage Teachers →
            </a>
        </div>

        <!-- Classes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Classes</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalClasses }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-green-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 7h18M3 12h18M3 17h18"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.classes.index') }}"
               class="inline-block mt-5 text-sm font-semibold text-green-600 hover:text-green-800">
                Manage Classes →
            </a>
        </div>

        <!-- Students -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Students</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalStudents }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-yellow-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m6-4a4 4 0 11-8 0 4 4 0 018 0zm6 0a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.students.index') }}"
               class="inline-block mt-5 text-sm font-semibold text-yellow-600 hover:text-yellow-800">
                Manage Students →
            </a>
        </div>

        <!-- Schedules -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Schedules</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">
                        {{ $totalSchedules }}
                    </h2>
                </div>

                <div class="w-14 h-14 rounded-2xl bg-red-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>

            <a href="{{ route('admin.schedules.index') }}"
               class="inline-block mt-5 text-sm font-semibold text-red-600 hover:text-red-800">
                Manage Schedules →
            </a>
        </div>

    </div>

    <!-- Dashboard Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Quick Actions -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-1">Quick Actions</h2>
            <p class="text-sm text-gray-500 mb-5">Common tasks for school management</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <a href="{{ route('admin.teachers.create') }}"
                   class="p-5 rounded-xl border border-gray-100 hover:border-blue-300 hover:bg-blue-50 transition">
                    <h3 class="font-bold text-gray-900">Add Teacher</h3>
                    <p class="text-sm text-gray-500 mt-1">Create a new teacher profile</p>
                </a>

                <a href="{{ route('admin.students.create') }}"
                   class="p-5 rounded-xl border border-gray-100 hover:border-yellow-300 hover:bg-yellow-50 transition">
                    <h3 class="font-bold text-gray-900">Add Student</h3>
                    <p class="text-sm text-gray-500 mt-1">Register a new student</p>
                </a>

                <a href="{{ route('admin.classes.create') }}"
                   class="p-5 rounded-xl border border-gray-100 hover:border-green-300 hover:bg-green-50 transition">
                    <h3 class="font-bold text-gray-900">Add Class</h3>
                    <p class="text-sm text-gray-500 mt-1">Create a new class</p>
                </a>

                <a href="{{ route('admin.schedules.create') }}"
                   class="p-5 rounded-xl border border-gray-100 hover:border-red-300 hover:bg-red-50 transition">
                    <h3 class="font-bold text-gray-900">Add Schedule</h3>
                    <p class="text-sm text-gray-500 mt-1">Upload a class or teacher schedule</p>
                </a>

            </div>
        </div>

        <!-- System Summary -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-1">System Summary</h2>
            <p class="text-sm text-gray-500 mb-5">Current school data overview</p>

            <div class="space-y-4">
                <div class="flex justify-between border-b pb-3">
                    <span class="text-gray-500">Teachers</span>
                    <span class="font-bold text-gray-900">{{ $totalTeachers }}</span>
                </div>

                <div class="flex justify-between border-b pb-3">
                    <span class="text-gray-500">Classes</span>
                    <span class="font-bold text-gray-900">{{ $totalClasses }}</span>
                </div>

                <div class="flex justify-between border-b pb-3">
                    <span class="text-gray-500">Students</span>
                    <span class="font-bold text-gray-900">{{ $totalStudents }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Schedules</span>
                    <span class="font-bold text-gray-900">{{ $totalSchedules }}</span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
