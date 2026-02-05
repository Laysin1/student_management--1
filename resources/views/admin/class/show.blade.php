@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('classes.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>
    <h1 class="text-2xl font-bold text-gray-900">Delete Classes</h1>
  </div>

  <!-- Messages -->
  @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
  @endif

  <!-- Class List -->
  <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <table class="w-full">
      <thead class="bg-gray-50 border-b border-gray-200">
        <tr>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Class Name</th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Grade Level</th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Students</th>
          <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Teachers</th>
          <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($classes as $class)
          <tr class="border-b border-gray-100 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }} hover:bg-red-50/30 transition">
            <td class="px-6 py-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-semibold text-sm">
                  {{ strtoupper(substr($class->name, 0, 2)) }}
                </div>
                <div>
                  <div class="text-gray-900 font-medium">{{ $class->name }}</div>
                  <div class="text-xs text-gray-500">ID: #{{ $class->id }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 text-gray-700">{{ $class->grade_level ?? '—' }}</td>
            <td class="px-6 py-4 text-gray-700">{{ $class->students_count ?? $class->students()->count() }}</td>
            <td class="px-6 py-4 text-gray-700">{{ $class->teachers_count ?? $class->teachers()->count() }}</td>
            <td class="px-6 py-4 text-center">
              <form action="{{ route('classes.destroy', $class->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete {{ $class->name }}? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold text-sm transition">
                  Delete
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No classes found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Pagination -->
  @if($classes->hasPages())
    <div class="mt-6">
      {{ $classes->links() }}
    </div>
  @endif
</div>
@endsection
