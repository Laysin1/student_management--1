@extends('layouts.teacher')

@section('content')
<div class="py-8 bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="font-bold text-4xl text-gray-900 mb-2">My Schedule</h2>
            <p class="text-gray-600 text-lg">View your class schedules</p>
        </div>

        <!-- Filter schedules to only show assigned teacher's classes or direct teacher schedules -->
        @php
            $filteredSchedules = $schedules->filter(function($schedule) {
                $teacherId = auth()->user()->teacher->id;

                // Show if it's assigned to the teacher directly
                if ($schedule->teacher_id === $teacherId) {
                    return true;
                }

                // OR show if it's for a class assigned to the teacher
                if ($schedule->class && $schedule->class->teacher_id === $teacherId) {
                    return true;
                }

                return false;
            })->values();
        @endphp

        <!-- Schedule Images Grid -->
        @if($filteredSchedules && count($filteredSchedules) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($filteredSchedules as $schedule)
                    <div class="group">
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transform hover:-translate-y-2 transition duration-300">
                            <!-- Image Container -->
                            <div class="relative overflow-hidden bg-gray-100 h-80">
                                @if(!empty($schedule->photo_path))
                                    <a href="{{ asset('storage/'.$schedule->photo_path) }}" target="_blank" class="block w-full h-full">
                                        <img src="{{ asset('storage/'.$schedule->photo_path) }}" alt="Schedule" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition duration-300"></div>
                                    </a>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-500 text-sm">No image</span>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Footer -->
                            <div class="p-4 bg-white">
                                @if($schedule->class)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900">{{ $schedule->class->name }}</h3>
                                            <p class="text-xs text-gray-500">Grade {{ $schedule->class->grade_level }}</p>
                                        </div>
                                        <a href="{{ route('teacher.classes.show', $schedule->class->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900">Teacher Schedule</h3>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="flex items-center justify-center py-20">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-white shadow-lg mb-4">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">No Schedule Yet</h3>
                    <p class="text-gray-600 mb-6">Your schedule images will appear here once they are uploaded.</p>
                    <div class="inline-block px-6 py-2 bg-white rounded-lg shadow text-sm text-gray-600">
                        Check back soon! 📅
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
