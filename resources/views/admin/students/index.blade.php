@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
  <!-- Back to Classes -->
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('classes.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <!-- left arrow -->
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>

    <a href="{{ route('students.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow font-semibold">
      + Add Student
    </a>
  </div>

  <form method="GET" action="{{ route('students.index') }}" class="bg-white rounded-lg shadow p-4 mb-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for a student by name or email" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
      <select name="class_id" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        <option value="">All Classes</option>
        @foreach($classes as $cls)
          <option value="{{ $cls->id }}" {{ (string)request('class_id') === (string)$cls->id ? 'selected' : '' }}>
            {{ $cls->name }} ({{ $cls->grade_level }})
          </option>
        @endforeach
      </select>
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
    </div>
  </form>

  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-100 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student ID</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email address</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Class</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Grade</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Age</th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($students as $student)
            <tr class="border-b border-gray-200 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  @php $initials = strtoupper(substr($student->first_name,0,1).substr($student->last_name,0,1)); @endphp
                  <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                    {{ $initials }}
                  </div>
                  <span class="text-gray-800 font-medium">{{ $student->first_name }} {{ $student->last_name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-gray-700">{{ $student->student_id ?? '—' }}</td>
              <td class="px-6 py-4 text-gray-700">{{ $student->user->email ?? '—' }}</td>
              <td class="px-6 py-4 text-gray-700">{{ $student->class->name ?? '—' }}</td>
              <td class="px-6 py-4 text-gray-700">{{ $student->class->grade_level ?? '—' }}</td>
              <td class="px-6 py-4 text-gray-700">{{ $student->gender ?? '—' }}</td>
              <td class="px-6 py-4 text-gray-700">
                @if($student->date_of_birth)
                  {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}
                @else
                  —
                @endif
              </td>
              <td class="px-6 py-4 text-center">
                <div class="flex justify-center gap-2">
                  <a href="{{ route('students.show', $student->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold">View</a>
                  <a href="{{ route('students.edit', $student->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-semibold">Edit</a>
                  <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this student?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold">Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-6 py-8 text-center text-gray-500">No students found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-6">
    {{ $students->links() }}
  </div>
</div>
@endsection
