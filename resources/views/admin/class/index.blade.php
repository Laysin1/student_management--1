@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
  <div class="flex items-center justify-between mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Classes</h1>
    <a href="{{ route('classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow font-semibold">
      + Add Class
    </a>
  </div>

  @php
    $data = $gradeSummary ?? collect();
  @endphp

  @if($data->isEmpty())
    <div class="text-center text-gray-500 mt-10">
      No grades available. <a href="{{ route('classes.create') }}" class="text-blue-600 hover:underline">Add a class</a>
    </div>
  @else
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($data as $grade)
        @php
          $sectionsText = implode(', ', $grade['sections']);
          $firstId = $grade['first_id'] ?? null;
        @endphp
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col">
          <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-900">{{ $grade['grade_level'] }}</h2>
            <p class="text-gray-600 mt-1">{{ $grade['classes_count'] }} Classes</p>
            <p class="text-gray-700 mt-2">
              <span class="font-medium">Sections:</span> {{ $sectionsText ?: '—' }}
            </p>
          </div>

          <div class="mt-auto pt-4 border-t border-gray-100">
            <div class="flex gap-2">
              @if($firstId)
                <!-- View -> Students index with class filter -->
                <a href="{{ route('students.index', ['class_id' => $firstId]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold">View</a>
                <a href="{{ route('classes.edit', $firstId) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-semibold">Edit</a>
                <a href="{{ route('classes.delete-list') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">
                Delete
                </a>
              @else
                <a href="{{ route('classes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-semibold">Add Class</a>
              @endif
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif
</div>
@endsection
