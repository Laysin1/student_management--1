@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <p class="mt-2 text-blue-100">
            Welcome to the RUPP Admin Panel. Here is today’s school system overview.
        </p>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Teachers</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalTeachers }}</h2>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">👨‍🏫</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Classes</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalClasses }}</h2>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">🏫</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Students</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalStudents }}</h2>
                </div>
                <div class="w-14 h-14 bg-yellow-100 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">🎓</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Total Schedules</p>
                    <h2 class="mt-2 text-3xl font-bold text-gray-900">{{ $totalSchedules }}</h2>
                </div>
                <div class="w-14 h-14 bg-red-100 rounded-2xl flex items-center justify-center">
                    <span class="text-2xl">📅</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Summary Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-2">System Overview</h2>
            <p class="text-gray-500 text-sm mb-6">
                This dashboard gives a quick summary of the school management system.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-blue-50">
                    <p class="text-sm text-blue-600 font-semibold">Teachers</p>
                    <p class="text-gray-700 mt-1">Manage teacher profiles and subjects.</p>
                </div>

                <div class="p-4 rounded-xl bg-green-50">
                    <p class="text-sm text-green-600 font-semibold">Classes</p>
                    <p class="text-gray-700 mt-1">Organize classes by grade level.</p>
                </div>

                <div class="p-4 rounded-xl bg-yellow-50">
                    <p class="text-sm text-yellow-600 font-semibold">Students</p>
                    <p class="text-gray-700 mt-1">View and manage student information.</p>
                </div>

                <div class="p-4 rounded-xl bg-red-50">
                    <p class="text-sm text-red-600 font-semibold">Schedules</p>
                    <p class="text-gray-700 mt-1">Upload class and teacher schedules.</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Today</h2>

            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Date</p>
                    <p class="font-bold text-gray-900">{{ now()->format('M d, Y') }}</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Panel</p>
                    <p class="font-bold text-gray-900">RUPP Admin</p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">Status</p>
                    <span class="inline-block mt-1 px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                        Active
                    </span>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
