@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-3xl">
  <h1 class="text-2xl font-bold mb-2">Edit Schedule</h1>
  <p class="text-gray-600 mb-6">Update schedule and image.</p>

  @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
      <ul class="list-disc pl-5">
        @foreach ($errors->all() as $error)
          <li class="text-sm">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('schedules.update', $schedule->id) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-5">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Title</label>
        <input type="text" name="title" value="{{ old('title', $schedule->title) }}" class="border border-gray-300 rounded px-3 py-2 w-full">
      </div>
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Type <span class="text-red-500">*</span></label>
        <select name="type" id="type" class="border border-gray-300 rounded px-3 py-2 w-full" required>
          <option value="class" {{ old('type', $schedule->type)==='class'?'selected':'' }}>Class</option>
          <option value="teacher" {{ old('type', $schedule->type)==='teacher'?'selected':'' }}>Teacher</option>
        </select>
      </div>
    </div>

    <div id="classSelect" class="{{ old('type', $schedule->type)==='class' ? '' : 'hidden' }}">
      <label class="font-semibold text-gray-800 mb-2 block">Class <span class="text-red-500">*</span></label>
      <select name="class_id" class="border border-gray-300 rounded px-3 py-2 w-full">
        <option value="">Select class</option>
        @foreach(($classes ?? []) as $class)
          <option value="{{ $class->id }}" {{ (string)old('class_id', $schedule->class_id) === (string)$class->id ? 'selected' : '' }}>
            {{ $class->name }} ({{ $class->grade_level }})
          </option>
        @endforeach
      </select>
    </div>

    <div id="teacherSelect" class="{{ old('type', $schedule->type)==='teacher' ? '' : 'hidden' }}">
      <label class="font-semibold text-gray-800 mb-2 block">Teacher <span class="text-red-500">*</span></label>
      <select name="teacher_id" class="border border-gray-300 rounded px-3 py-2 w-full">
        <option value="">Select teacher</option>
        @foreach(($teachers ?? []) as $t)
          <option value="{{ $t->id }}" {{ (string)old('teacher_id', $schedule->teacher_id) === (string)$t->id ? 'selected' : '' }}>
            {{ $t->first_name }} {{ $t->last_name }} — {{ optional($t->subject)->name }}
          </option>
        @endforeach
      </select>
    </div>

    <div>
      <label class="font-semibold text-gray-800 mb-2 block">Schedule Image</label>
      @if(!empty($schedule->photo_path))
        <img src="{{ asset('storage/'.$schedule->photo_path) }}" alt="Schedule" class="w-full max-h-64 object-contain border rounded mb-2">
      @endif
      <input type="file" name="photo" accept="image/*" class="border border-gray-300 rounded px-3 py-2 w-full">
      <small class="text-gray-500">Upload a new image to replace the current one (PNG/JPG up to 4 MB).</small>
    </div>

    <div class="flex gap-3">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Save</button>
      <a href="{{ route('schedules.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded font-semibold">Cancel</a>
    </div>
  </form>

  <script>
    const typeSel = document.getElementById('type');
    const classBox = document.getElementById('classSelect');
    const teacherBox = document.getElementById('teacherSelect');
    typeSel.addEventListener('change', () => {
      const v = typeSel.value;
      classBox.classList.toggle('hidden', v !== 'class');
      teacherBox.classList.toggle('hidden', v !== 'teacher');
    });
  </script>
</div>
@endsection
