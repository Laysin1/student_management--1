@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">

  <!-- Header -->
  <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-bold">Schedules</h1>
        <p class="mt-2 text-blue-100">
          Manage class schedules and teacher schedules.
        </p>
      </div>

      <a href="{{ route('admin.schedules.create') }}"
         class="bg-white text-blue-700 hover:bg-blue-50 px-5 py-3 rounded-xl font-semibold text-center">
        + Add Schedule
      </a>
    </div>
  </div>

  <!-- Flash messages -->
  @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4">
      {{ session('error') }}
    </div>
  @endif

  <!-- Search / Filter -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <form action="{{ route('admin.schedules.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

      <div class="md:col-span-2">
        <label class="text-sm font-semibold text-gray-700 mb-2 block">
          Search Schedule
        </label>
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search title, class, teacher..."
               class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
      </div>

      <div>
        <label class="text-sm font-semibold text-gray-700 mb-2 block">
          Schedule Type
        </label>
        <select name="type"
                class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
          <option value="">All Schedules</option>
          <option value="class" {{ request('type') === 'class' ? 'selected' : '' }}>Class Schedule</option>
          <option value="teacher" {{ request('type') === 'teacher' ? 'selected' : '' }}>Teacher Schedule</option>
        </select>
      </div>

      <div class="flex items-end gap-2">
        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-semibold">
          Search
        </button>

        @if(request('search') || request('type'))
          <a href="{{ route('admin.schedules.index') }}"
             class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-xl font-semibold">
            Reset
          </a>
        @endif
      </div>

    </form>
  </div>

  <!-- Quick Filter Buttons -->
  <div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('admin.schedules.index') }}"
       class="px-4 py-2 rounded-xl font-semibold {{ !request('type') ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border' }}">
      All
    </a>

    <a href="{{ route('admin.schedules.index', ['type' => 'class']) }}"
       class="px-4 py-2 rounded-xl font-semibold {{ request('type') === 'class' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border' }}">
      Class Schedules
    </a>

    <a href="{{ route('admin.schedules.index', ['type' => 'teacher']) }}"
       class="px-4 py-2 rounded-xl font-semibold {{ request('type') === 'teacher' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 border' }}">
      Teacher Schedules
    </a>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    <div class="px-6 py-5 border-b border-gray-100">
      <h2 class="text-xl font-bold text-gray-900">Schedule List</h2>
      <p class="text-sm text-gray-500">
        Total: {{ $schedules->total() }} schedules
      </p>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Title</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Type</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600 min-w-[250px]">Target</th>
            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Image</th>
            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Actions</th>
          </tr>
        </thead>

        <tbody>
          @forelse($schedules as $schedule)
            <tr class="border-b border-gray-100 hover:bg-gray-50">

              <!-- Title -->
              <td class="px-6 py-4">
                <p class="font-bold text-gray-900 whitespace-nowrap">
                  {{ $schedule->title ?? 'Untitled Schedule' }}
                </p>
              </td>

              <!-- Type -->
              <td class="px-6 py-4">
                @if($schedule->class_id)
                  <span class="inline-flex px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold whitespace-nowrap">
                    Class
                  </span>
                @elseif($schedule->teacher_id)
                  <span class="inline-flex px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold whitespace-nowrap">
                    Teacher
                  </span>
                @else
                  <span class="text-gray-400">—</span>
                @endif
              </td>

              <!-- Target -->
              <td class="px-6 py-4">
                @if($schedule->class_id && $schedule->class)
                  <div>
                    <p class="font-semibold text-gray-900">
                      {{ $schedule->class->name }}
                    </p>
                    <p class="text-sm text-gray-500">
                      {{ $schedule->class->grade_level }}
                    </p>
                  </div>
                @elseif($schedule->teacher_id && $schedule->teacher)
                  <div>
                    <p class="font-semibold text-gray-900">
                      {{ $schedule->teacher->first_name }} {{ $schedule->teacher->last_name }}
                    </p>

                    @if($schedule->teacher->subject)
                      <p class="text-sm text-gray-500">
                        {{ $schedule->teacher->subject->name }}
                      </p>
                    @endif
                  </div>
                @else
                  <span class="text-gray-400">No target</span>
                @endif
              </td>

              <!-- Image -->
              <td class="px-6 py-4">
                @if(!empty($schedule->photo_path))
                  <a href="{{ asset('storage/'.$schedule->photo_path) }}" target="_blank">
                    <img src="{{ asset('storage/'.$schedule->photo_path) }}"
                         alt="Schedule"
                         class="h-14 w-24 object-cover border rounded-lg hover:shadow-md transition">
                  </a>
                @else
                  <span class="text-gray-400 text-sm">No image</span>
                @endif
              </td>

              <!-- Actions -->
              <td class="px-6 py-4">
                <div class="flex justify-center gap-2">
                  <a href="{{ route('admin.schedules.show', $schedule->id) }}"
                     class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-semibold">
                    View
                  </a>

                  <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                     class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                    Edit
                  </a>

                  <form action="{{ route('admin.schedules.destroy', $schedule->id) }}"
                        method="POST"
                        onsubmit="return confirm('Delete this schedule?');">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                      Delete
                    </button>
                  </form>
                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                No schedules found.
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
