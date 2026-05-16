@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-3xl">

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <a href="{{ route('schedules.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
            </svg>
            Back
        </a>

        <a href="{{ route('schedules.edit', $schedule->id) }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
            Edit
        </a>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-xl shadow p-6 border border-gray-100">

        <h1 class="text-2xl font-bold text-gray-900 mb-4">
            {{ $schedule->title ?? 'Schedule #'.$schedule->id }}
        </h1>

        <!-- Target Info -->
        <div class="space-y-3 mb-6">

            @if($schedule->teacher_id && $schedule->teacher)
                <div class="flex items-center gap-2 text-gray-700">
                    <span class="px-3 py-1 bg-purple-100 text-purple-800 text-xs rounded-full font-semibold">
                        Teacher
                    </span>

                    <span>
                        {{ $schedule->teacher->first_name }} {{ $schedule->teacher->last_name }}

                        @if($schedule->teacher->subject)
                            — {{ $schedule->teacher->subject->name }}
                        @endif
                    </span>
                </div>
            @endif

            @if($schedule->class_id && $schedule->class)
                <div class="flex items-center gap-2 text-gray-700">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs rounded-full font-semibold">
                        Class
                    </span>

                    <span>
                        {{ $schedule->class->name }}

                        @if($schedule->class->grade_level)
                            ({{ $schedule->class->grade_level }})
                        @endif
                    </span>
                </div>
            @endif

            @if(!$schedule->teacher_id && !$schedule->class_id)
                <span class="text-gray-500">
                    No target assigned
                </span>
            @endif

        </div>

        <!-- Image preview -->
        @if(!empty($schedule->photo_path))
            <div class="mt-6">
                <div class="text-sm font-semibold text-gray-700 mb-3">
                    Schedule Image
                </div>

                <a href="{{ asset('storage/'.$schedule->photo_path) }}" target="_blank">
                    <img
                        src="{{ asset('storage/'.$schedule->photo_path) }}"
                        alt="Schedule"
                        class="w-full max-h-[600px] object-contain border rounded-lg shadow-md hover:shadow-lg transition"
                    >
                </a>

                <div class="mt-3">
                    <a href="{{ asset('storage/'.$schedule->photo_path) }}"
                       target="_blank"
                       class="text-blue-600 hover:underline text-sm font-semibold">
                        → Open full size
                    </a>
                </div>
            </div>
        @else
            <div class="mt-6 p-4 bg-gray-50 rounded-lg text-gray-500 text-center">
                No image uploaded
            </div>
        @endif

    </div>
</div>
@endsection
