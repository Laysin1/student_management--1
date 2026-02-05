@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
  <div class="flex items-center gap-3 mb-6">
    <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>
  </div>

  <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Add Class</h1>
  <p class="text-gray-600 mb-6">Manually</p>

  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li class="text-sm">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form id="classForm" action="{{ route('classes.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-5">
    @csrf

    <!-- Class + Grade -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="flex flex-col">
        <label class="font-semibold text-gray-800 mb-2">Class <span class="text-red-500">*</span></label>
        <input type="text" name="name" id="class_name" value="{{ old('name') }}" placeholder="e.g., 11A-Math, Section C" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
      </div>
      <div class="flex flex-col">
        <label class="font-semibold text-gray-800 mb-2">Grade <span class="text-red-500">*</span></label>
        <select name="grade_level" id="grade_level" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
          <option value="">Select grade</option>
          @foreach(($grades ?? ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12']) as $grade)
            <option value="{{ $grade }}" {{ old('grade_level') === $grade ? 'selected' : '' }}>{{ $grade }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <!-- Select Schedule -->
    <div class="flex flex-col">
      <label class="font-semibold text-gray-800 mb-2">Schedule</label>
      <select name="schedule_id" id="schedule_id" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        <option value="">Select schedule (optional)</option>
        @foreach(($schedules ?? []) as $schedule)
          <option value="{{ $schedule->id }}" {{ (string)old('schedule_id') === (string)$schedule->id ? 'selected' : '' }}>
            {{ $schedule->title ?? 'Schedule #'.$schedule->id }}
            @if($schedule->type)
              ({{ ucfirst($schedule->type) }})
            @endif
          </option>
        @endforeach
      </select>
      <small class="text-gray-500 mt-1">Select an existing schedule to assign to this class.</small>
    </div>

    <div class="flex gap-3 pt-2">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Add</button>
      <a href="{{ route('classes.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded font-semibold">Cancel</a>
    </div>
  </form>
</div>
@endsection
