@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('teachers.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>
    <div class="flex gap-2">
      <a href="{{ route('teachers.edit', $teacher->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">Edit</a>
      <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Delete this teacher?');">
        @csrf @method('DELETE')
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">Delete</button>
      </form>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow p-6 border border-gray-100">
    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $teacher->first_name }} {{ $teacher->last_name }}</h1>
    <div class="text-gray-600 mb-4">
      {{ optional($teacher->subject)->name ?? '—' }}
      @if($teacher->classes && $teacher->classes->count())
        •
        @foreach($teacher->classes as $class)
          {{ $class->name }} ({{ $class->grade_level }}){{ !$loop->last ? ', ' : '' }}
        @endforeach
      @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <div class="text-sm text-gray-500">Email</div>
        <div class="text-gray-800">{{ optional($teacher->user)->email ?? '—' }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Phone</div>
        <div class="text-gray-800">{{ $teacher->phone_number ?? '—' }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Gender</div>
        <div class="text-gray-800">{{ $teacher->gender ?? '—' }}</div>
      </div>
      <div>
        <div class="text-sm text-gray-500">Teacher ID</div>
        <div class="text-gray-800">#{{ $teacher->id }}</div>
      </div>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow p-6 border border-gray-100 mt-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Classes Assigned</h3>

    @if($teacher->classes && $teacher->classes->count())
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($teacher->classes as $class)
          <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
            <div class="flex items-start justify-between">
              <div>
                <h4 class="font-semibold text-gray-900">{{ $class->name }}</h4>
                <p class="text-sm text-gray-600">Grade: {{ $class->grade_level }}</p>
                <p class="text-sm text-gray-500 mt-1">Students: {{ $class->students->count() }}</p>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <p class="text-gray-500">No classes assigned to this teacher.</p>
    @endif
  </div>
</div>
@endsection
