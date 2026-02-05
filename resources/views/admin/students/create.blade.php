@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('students.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>
    <div class="w-64">
      <label class="font-semibold text-gray-800 mb-2 block">Student ID</label>
      <input type="text" name="student_id" form="studentForm" value="{{ old('student_id') }}" placeholder="e.g., STU-000123" class="border border-gray-300 rounded px-3 py-2 w-full">
    </div>
  </div>

  <h1 class="text-3xl font-bold text-gray-900">Add Student</h1>
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

  <form id="studentForm" action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded-lg p-6 space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">First Name <span class="text-red-500">*</span></label>
        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="e.g., John" class="border border-gray-300 rounded px-3 py-2 w-full" required>
      </div>
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Last Name <span class="text-red-500">*</span></label>
        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="e.g., Doe" class="border border-gray-300 rounded px-3 py-2 w-full" required>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Date of Birth <span class="text-red-500">*</span></label>
        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="border border-gray-300 rounded px-3 py-2 w-full" required>
      </div>
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" class="border border-gray-300 rounded px-3 py-2 w-full" required>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Email address <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" class="border border-gray-300 rounded px-3 py-2 w-full" required>
      </div>
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Phone Number</label>
        <input type="tel" name="phone_number" value="{{ old('phone_number') }}" placeholder="+1 555-123-4567" class="border border-gray-300 rounded px-3 py-2 w-full">
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Class</label>
        <select name="class_id" class="border border-gray-300 rounded px-3 py-2 w-full">
          <option value="">Select Class</option>
          @foreach(($classes ?? \App\Models\SchoolClass::select('id','name','grade_level')->orderBy('grade_level')->get()) as $cls)
            <option value="{{ $cls->id }}" {{ old('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }} ({{ $cls->grade_level }})</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Gender</label>
        <select name="gender" class="border border-gray-300 rounded px-3 py-2 w-full">
          <option value="">Select Gender</option>
          <option value="Male" {{ old('gender')==='Male'?'selected':'' }}>Male</option>
          <option value="Female" {{ old('gender')==='Female'?'selected':'' }}>Female</option>
          <option value="Other" {{ old('gender')==='Other'?'selected':'' }}>Other</option>
        </select>
      </div>
      <div>
        <label class="font-semibold text-gray-800 mb-2 block">Parent Number</label>
        <input type="tel" name="parent_number" value="{{ old('parent_number') }}" placeholder="+1 555-987-6543" class="border border-gray-300 rounded px-3 py-2 w-full">
      </div>
    </div>

    <div>
      <label class="font-semibold text-gray-800 mb-2 block">Profile picture</label>
      <div class="flex items-center gap-3">
        <label for="profile_photo" class="inline-flex items-center bg-gray-100 hover:bg-gray-200 border border-gray-300 text-gray-800 px-4 py-2 rounded cursor-pointer">Browse</label>
        <input type="file" id="profile_photo" name="profile_photo" class="hidden" accept="image/*">
        <span id="file_name" class="text-gray-600 text-sm">No file selected</span>
      </div>
      <small class="text-gray-500 mt-1 block">Accepted: JPG, PNG. Max 2MB.</small>
    </div>

    <div class="flex gap-3 pt-2">
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">Save</button>
      <a href="{{ route('students.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded font-semibold">Cancel</a>
    </div>
  </form>
</div>

<script>
  const fileInput = document.getElementById('profile_photo');
  const fileNameEl = document.getElementById('file_name');
  fileInput?.addEventListener('change', () => {
    const f = fileInput.files[0];
    fileNameEl.textContent = f ? f.name : 'No file selected';
  });
</script>
@endsection
