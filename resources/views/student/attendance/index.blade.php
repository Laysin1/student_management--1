@extends('layouts.student')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">
            My Attendance
        </h2>

        <!-- Attendance Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm">Present</p>
                <p class="text-3xl font-bold text-green-600 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">days</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <p class="text-gray-600 text-sm">Absent</p>
                <p class="text-3xl font-bold text-red-600 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">days</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm">Permission</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">0</p>
                <p class="text-xs text-gray-500 mt-1">days</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
                <p class="text-gray-600 text-sm">Attendance Rate</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">—</p>
                <p class="text-xs text-gray-500 mt-1">percentage</p>
            </div>
        </div>

        <!-- Attendance Records Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Attendance Records</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Day</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Remarks</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No attendance records yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Attendance Policy Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <h4 class="text-sm font-semibold text-blue-900 mb-3">📋 Attendance Policy</h4>
            <ul class="text-sm text-blue-900 space-y-2">
                <li>✓ Students must maintain at least 75% attendance to be eligible for exams</li>
                <li>✓ Permission (approved leave) does not count as absent</li>
                <li>✓ Regular absences may affect your academic performance and grades</li>
                <li>✓ Contact your teacher or parents if you have any concerns</li>
            </ul>
        </div>
    </div>
</div>
@endsection
