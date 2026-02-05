@extends('layouts.admin')
@section('content')
<div class="container mx-auto px-6 py-8">
  <!-- Header -->
  <div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Schedules</h1>
    <a href="{{ route('schedules.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold">
      Add Schedule
    </a>
  </div>

  <!-- Flash messages -->
  @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
  @endif

  <!-- Table -->
  <div class="bg-white rounded-xl shadow overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">For</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Target</th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Image</th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($schedules as $schedule)
            <tr class="border-b border-gray-100 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
              <td class="px-6 py-4 text-gray-700">#{{ $schedule->id }}</td>
              <td class="px-6 py-4 text-gray-900 font-medium">{{ $schedule->title ?? '—' }}</td>
              <td class="px-6 py-4 text-gray-700">
                @if($schedule->class_id)
                  <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded font-semibold">Class</span>
                @elseif($schedule->teacher_id)
                  <span class="inline-block px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded font-semibold">Teacher</span>
                @else
                  <span class="text-gray-500">—</span>
                @endif
              </td>
              <td class="px-6 py-4 text-gray-700">
                @if($schedule->class_id && $schedule->class)
                  {{ $schedule->class->name }} ({{ $schedule->class->grade_level }})
                @elseif($schedule->teacher_id && $schedule->teacher)
                  {{ $schedule->teacher->first_name }} {{ $schedule->teacher->last_name }}
                  @if($schedule->teacher->subject)
                    — {{ $schedule->teacher->subject->name }}
                  @endif
                @else
                  —
                @endif
              </td>
              <td class="px-6 py-4">
                @if(!empty($schedule->photo_path))
                  <a href="{{ asset('storage/'.$schedule->photo_path) }}" target="_blank">
                    <img src="{{ asset('storage/'.$schedule->photo_path) }}" alt="Schedule" class="h-12 w-20 object-cover border rounded hover:shadow-md transition">
                  </a>
                @else
                  <span class="text-gray-500 text-sm">No image</span>
                @endif
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center gap-2">
                  <a href="{{ route('schedules.show', $schedule->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">View</a>
                  <a href="{{ route('schedules.edit', $schedule->id) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">Edit</a>
                  <form action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Delete this schedule?');" class="inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                No schedules found. <a href="{{ route('schedules.create') }}" class="text-blue-600 hover:underline">Create one</a>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Pagination -->
  <div class="mt-6">
    {{ $schedules->links() }}
  </div>
</div>
@endsection
