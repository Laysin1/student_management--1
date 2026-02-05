@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-3xl">
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('students.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
            </svg>
            Back
        </a>
        <div class="flex gap-2">
            <a href="{{ route('students.edit', $student->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Edit</a>
            <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Delete this student?');">
                @csrf @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">Delete</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $student->first_name }} {{ $student->last_name }}</h1>
        <div class="text-gray-600 mb-4">
            <span class="font-semibold">Class:</span> {{ $student->class->name ?? '—' }}
            @if(optional($student->class)->grade_level)
                • <span class="font-semibold">Grade:</span> {{ $student->class->grade_level }}
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <div class="text-sm text-gray-500">Student ID</div>
                <div class="text-gray-800">{{ $student->student_id ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Email</div>
                <div class="text-gray-800">{{ $student->user->email ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Gender</div>
                <div class="text-gray-800">{{ $student->gender ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Age</div>
                <div class="text-gray-800">
                  @if($student->date_of_birth)
                    {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}
                  @else
                    —
                  @endif
                </div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Phone</div>
                <div class="text-gray-800">{{ $student->phone_number ?? '—' }}</div>
            </div>
            <div>
                <div class="text-sm text-gray-500">Address</div>
                <div class="text-gray-800">{{ $student->address ?? '—' }}</div>
            </div>
        </div>
    </div>
</div>
