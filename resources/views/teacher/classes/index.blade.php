@extends('layouts.teacher')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Class Management
        </h1>
        <p class="text-gray-600 mt-1">
            Manage your assigned classes, attendance, and student scores.
        </p>
    </div>

    <div class="bg-white rounded-xl shadow border border-gray-100 p-2 mb-6">
        <div class="flex flex-col md:flex-row gap-2">
            <a href="{{ route('teacher.classes.index') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center bg-blue-600 text-white">
                Classes
            </a>

            <a href="{{ route('teacher.classes.attend') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Attendance
            </a>

            <a href="{{ route('teacher.classes.scores') }}"
               class="px-5 py-3 rounded-lg font-semibold text-center text-gray-600 hover:bg-gray-100 hover:text-blue-600">
                Scores
            </a>
        </div>
    </div>

    @if(isset($classes) && $classes->count())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach ($classes as $class)
                <div class="bg-white rounded-xl shadow border border-gray-100 p-6 hover:shadow-md transition">

                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">
                                {{ $class->name }}
                            </h3>

                            <p class="text-gray-600 text-sm mt-1">
                                Grade Level: {{ $class->grade_level ?? '—' }}
                            </p>
                        </div>

                        <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                            {{ $class->students->count() }}
                        </span>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-gray-500 text-sm mb-4">
                            {{ $class->students->count() }} student(s) assigned to this class.
                        </p>

                        <a href="{{ route('teacher.classes.show', $class->id) }}"
                           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg text-center">
                            View Students
                        </a>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white rounded-xl shadow border border-gray-100 p-10 text-center">
            <div class="text-gray-400 text-lg mb-1">
                No classes assigned yet
            </div>

            <p class="text-gray-500 text-sm">
                Once admin assigns classes to you, they will appear here.
            </p>
        </div>
    @endif

</div>
@endsection
