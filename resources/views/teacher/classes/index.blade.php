@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 mb-6">Class Management</h2>

        <!-- Tabs Navigation -->
        <div class="flex gap-4 mb-6 border-b border-gray-200">
            <a href="{{ route('teacher.classes.index') }}" class="px-6 py-3 font-semibold text-blue-600 border-b-2 border-blue-600">
                Classes
            </a>
            <a href="{{ route('teacher.classes.attend') }}" class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-600">
                Attendance
            </a>
            <a href="{{ route('teacher.classes.scores') }}" class="px-6 py-3 font-semibold text-gray-600 hover:text-blue-600">
                Scores
            </a>
        </div>

        <!-- Classes Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($classes as $class)
                @if($class->teacher_id === auth()->user()->teacher->id || $class->teacher_id == auth()->user()->teacher->id)
                    <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $class->grade_level }}
                        </h3>
                        <p class="text-gray-600 mb-2">{{ $class->name }}</p>
                        <p class="text-sm text-gray-500 mb-4">{{ $class->students->count() }} Students</p>
                        <div class="flex gap-2">
                            <a href="{{ route('teacher.classes.show', $class->id) }}" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded text-center text-sm">
                                View Students
                            </a>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-span-full">
                    <p class="text-center text-gray-500 py-8">No classes assigned to you yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
