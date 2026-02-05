@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
  <div class="flex items-center gap-3 mb-6">
    <a href="{{ route('classes.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">← Back</a>
  </div>

  <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Class</h1>
  <p class="text-gray-600 mb-6">{{ $class->name }}</p>

  @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li class="text-sm">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('classes.update', $class->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-5">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="flex flex-col">
        <label class="font-semibold text-gray-800 mb-2">Class <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $class->name) }}" class="border border-gray-300 rounded px-3 py-2" required>
      </div>
      <div class="flex flex-col">
        <label class="font-semibold text-gray-800 mb-2">Grade <span class="text-red-500">*</span></label>
        <select name="grade_level" class="border border-gray-300 rounded px-3 py-2" required>
          @foreach(($grades ?? ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12']) as $grade)
            <option value="{{ $grade }}" {{ old('grade_level', $class->grade_level) === $grade ? 'selected' : '' }}>{{ $grade }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="flex flex-col">
      <label class="font-semibold text-gray-800 mb-2">Upload schedule</label>
      <div class="flex items-center gap-3">
        <label for="schedule_file" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-800 px-4 py-2 rounded cursor-pointer">Select</label>
        <input type="file" name="schedule_file" id="schedule_file" class="hidden" accept=".pdf,.csv,.xlsx,.xls,.txt,.json">
        <span id="file_name" class="text-gray-600 text-sm">No file selected</span>
      </div>
    </div>

    <div class="flex gap-3 pt-2">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Save</button>
      <a href="{{ route('classes.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded font-semibold">Cancel</a>
    </div>
  </form>
</div>
@endsection
