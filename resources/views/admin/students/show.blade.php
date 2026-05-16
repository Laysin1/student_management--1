@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-5xl">

  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.students.index', ['class_id' => $student->class_id]) }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>

    <div class="flex gap-2">
      <a href="{{ route('admin.students.edit', $student->id) }}"
         class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
        Edit
      </a>

      <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST"
            onsubmit="return confirm('Delete this student?');">
        @csrf
        @method('DELETE')
        <button type="submit"
                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">
          Delete
        </button>
      </form>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center gap-5">
      <div>
        @if($student->profile_photo_path)
          <img src="{{ asset('storage/'.$student->profile_photo_path) }}"
               alt="Profile"
               class="w-24 h-24 rounded-full object-cover border">
        @else
          <div class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold text-2xl">
            {{ strtoupper(substr($student->first_name, 0, 1)) }}
          </div>
        @endif
      </div>

      <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
          {{ $student->first_name }} {{ $student->last_name }}
        </h1>

        <p class="text-gray-600 mt-1">
          <span class="font-semibold">Class:</span> {{ $student->class->name ?? '—' }}

          @if(optional($student->class)->grade_level)
            • <span class="font-semibold">Grade:</span> {{ $student->class->grade_level }}
          @endif
        </p>
      </div>
    </div>

    <div class="p-6">
      <h2 class="text-lg font-bold text-gray-900 mb-4">Student Information</h2>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
          <div class="text-sm text-gray-500">Student ID</div>
          <div class="text-gray-800 font-medium">{{ $student->student_id ?? '—' }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Email</div>
          <div class="text-gray-800 font-medium">{{ optional($student->user)->email ?? '—' }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Gender</div>
          <div class="text-gray-800 font-medium">{{ $student->gender ?? '—' }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Date of Birth</div>
          <div class="text-gray-800 font-medium">
            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d M Y') : '—' }}
          </div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Age</div>
          <div class="text-gray-800 font-medium">
            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->age : '—' }}
          </div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Phone Number</div>
          <div class="text-gray-800 font-medium">{{ $student->phone_number ?? '—' }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Parent Number</div>
          <div class="text-gray-800 font-medium">{{ $student->parent_number ?? '—' }}</div>
        </div>

        <div>
          <div class="text-sm text-gray-500">Address</div>
          <div class="text-gray-800 font-medium">{{ $student->address ?? '—' }}</div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
