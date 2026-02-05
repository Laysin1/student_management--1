@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <a href="{{ route('dashboard.admin') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
      </svg>
      Back
    </a>

    <a href="{{ route('teachers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg shadow font-semibold">
      + Add Teacher
    </a>
  </div>

  <!-- Filters -->
  <div class="bg-white rounded-lg shadow p-4 mb-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <input type="text" id="searchInput" value="" placeholder="Search by name or email"
             class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
      <select id="filterSubject" class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
        <option value="">All Subjects</option>
        @foreach(($subjects ?? []) as $sub)
          <option value="{{ $sub->id }}">{{ $sub->name }}</option>
        @endforeach
      </select>
      <button type="button" id="applyBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Filter</button>
    </div>
  </div>

  <!-- Table -->
  <div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-gray-100 border-b border-gray-200">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email address</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Subject</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Class</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Grade</th>
            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody id="teachersTableBody">
          @forelse($teachers as $t)
  <tr class="border-b border-gray-200 {{ $loop->even ? 'bg-gray-50' : 'bg-white' }}">
    <td class="px-6 py-4">
      <div class="flex items-center gap-3">
        @php $initials = strtoupper(substr($t->first_name,0,1).substr($t->last_name,0,1)); @endphp
        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
          {{ $initials }}
        </div>
        <span class="text-gray-800 font-medium">{{ $t->first_name }} {{ $t->last_name }}</span>
      </div>
    </td>
    <td class="px-6 py-4 text-gray-700">{{ optional($t->user)->email ?? '—' }}</td>
    <td class="px-6 py-4 text-gray-700">{{ optional($t->subject)->name ?? 'N/A' }}</td>
    <!-- Classes column -->
    <td class="px-6 py-4 text-gray-700">
      @if($t->classes && $t->classes->count())
        @foreach($t->classes as $class)
          <div>{{ $class->name }} ({{ $class->grade_level }})</div>
        @endforeach
      @else
        —
      @endif
    </td>
    <!-- Grades column -->
    <td class="px-6 py-4 text-gray-700">
      @if($t->classes && $t->classes->count())
        @foreach($t->classes as $class)
          <div>{{ $class->grade_level }}</div>
        @endforeach
      @else
        —
      @endif
    </td>
    <td class="px-6 py-4 text-gray-700">{{ $t->gender ?? '—' }}</td>
    <td class="px-6 py-4 text-center">
      <div class="flex justify-center gap-2">
        <a href="{{ route('teachers.show', $t->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-semibold">View</a>
        <a href="{{ route('teachers.edit', $t->id) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm font-semibold">Edit</a>
        <form action="{{ route('teachers.destroy', $t->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this teacher?');">
          @csrf
          @method('DELETE')
          <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm font-semibold">Delete</button>
        </form>
      </div>
    </td>
  </tr>
@empty
  <tr>
    <td colspan="7" class="px-6 py-8 text-center text-gray-500">No teachers found.</td>
  </tr>
@endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-6">
    {{ $teachers->links() }}
  </div>
</div>

<script>
  const searchInput = document.getElementById('searchInput');
  const filterSubject = document.getElementById('filterSubject');
  const applyBtn = document.getElementById('applyBtn');

  const applyFilter = () => {
    const params = new URLSearchParams();
    const sv = (searchInput.value || '').trim();
    const sid = filterSubject.value || '';
    if (sv) params.set('search', sv);
    if (sid) params.set('subject_id', sid);

    // Navigate to server-rendered filtered list (reliable fallback)
    const url = `{{ route('teachers.index') }}?${params.toString()}`;
    window.location.href = url;
  };

  applyBtn.addEventListener('click', applyFilter);
</script>
@endsection
