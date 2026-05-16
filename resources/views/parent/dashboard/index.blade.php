@extends('layouts.parent')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white mb-8 shadow">
            <h1 class="text-3xl font-bold">Welcome, Parent</h1>
            <p class="mt-2 text-blue-100">
                Stay updated with your children’s school progress, announcements, and schedule.
            </p>
        </div>

        <!-- Quick Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-500">My Children</p>
                <h2 class="mt-2 text-3xl font-bold text-blue-600">
                    {{ count($students) ?? 0 }}
                </h2>
                <p class="mt-2 text-xs text-gray-400">Students linked to your account</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-500">Today</p>
                <h2 class="mt-2 text-2xl font-bold text-green-600">
                    {{ now()->format('M d, Y') }}
                </h2>
                <p class="mt-2 text-xs text-gray-400">Current school day</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <p class="text-sm text-gray-500">School Year</p>
                <h2 class="mt-2 text-3xl font-bold text-purple-600">
                    2026
                </h2>
                <p class="mt-2 text-xs text-gray-400">Academic year</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Left Side -->
            <div class="lg:col-span-2 space-y-8">

                <!-- School Announcements -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">School Announcements</h2>
                            <p class="text-sm text-gray-500">Latest updates from the school</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="border-l-4 border-blue-500 bg-blue-50 p-4 rounded-r-xl">
                            <h3 class="font-semibold text-gray-900">Parent Meeting</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Parent meeting will be held this Friday at 9:00 AM.
                            </p>
                            <p class="text-xs text-gray-400 mt-2">Posted today</p>
                        </div>

                        <div class="border-l-4 border-green-500 bg-green-50 p-4 rounded-r-xl">
                            <h3 class="font-semibold text-gray-900">Exam Schedule Released</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                The semester exam schedule is now available. Please check your children’s schedule.
                            </p>
                            <p class="text-xs text-gray-400 mt-2">2 days ago</p>
                        </div>

                        <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-r-xl">
                            <h3 class="font-semibold text-gray-900">School Holiday</h3>
                            <p class="text-sm text-gray-600 mt-1">
                                School will be closed next Monday for a public holiday.
                            </p>
                            <p class="text-xs text-gray-400 mt-2">This week</p>
                        </div>
                    </div>
                </div>

                <!-- Children Quick Access -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-1">Children Quick Access</h2>
                    <p class="text-sm text-gray-500 mb-5">
                        Quickly open grades or attendance for each child.
                    </p>

                    <div class="space-y-4">
                        @forelse($students as $student)
                            <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4 hover:bg-gray-50 transition">
                                <div>
                                    <h3 class="font-bold text-gray-900">
                                        {{ $student->first_name }} {{ $student->last_name }}
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        Student ID: {{ $student->student_id }}
                                    </p>
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('parent.grades', ['studentId' => $student->id]) }}"
                                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold">
                                        Grades
                                    </a>

                                    <a href="{{ route('parent.attendance', ['studentId' => $student->id]) }}"
                                       class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold">
                                        Attendance
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">No children linked to your account yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>

            <!-- Right Side -->
            <div class="space-y-8">

                <!-- Quick Actions -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <h2 class="text-xl font-bold text-gray-900 mb-5">Quick Actions</h2>

    <div class="space-y-3">
        <a href="/parent/children"
           class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold">
            View Children
        </a>

        <a href="/parent/schedule"
           class="block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-semibold">
            View Schedule
        </a>
    </div>
</div>

                <!-- Upcoming Events -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-5">Upcoming Events</h2>

                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                                20
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Parent Meeting</h3>
                                <p class="text-sm text-gray-500">Friday, 9:00 AM</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold">
                                25
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">Semester Exam</h3>
                                <p class="text-sm text-gray-500">Next week</p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div class="w-12 h-12 rounded-xl bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold">
                                30
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900">School Holiday</h3>
                                <p class="text-sm text-gray-500">No classes</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
