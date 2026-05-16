@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Teachers</h1>
                <p class="mt-2 text-blue-100">
                    Manage teacher profiles, subjects, and assigned classes.
                </p>
            </div>

            <a href="{{ route('admin.teachers.create') }}"
               class="bg-white text-blue-700 hover:bg-blue-50 px-5 py-3 rounded-xl font-semibold shadow text-center">
                + Add Teacher
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <form action="{{ route('admin.teachers.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                    Search Teacher
                </label>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                    Subject
                </label>

                <select name="subject_id"
                        onchange="this.form.submit()"
                        class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Subjects</option>

                    @foreach(($subjects ?? []) as $sub)
                        <option value="{{ $sub->id }}"
                            {{ (string) request('subject_id') === (string) $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-semibold">
                    Search
                </button>

                @if(request('search') || request('subject_id'))
                    <a href="{{ route('admin.teachers.index') }}"
                       class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-xl font-semibold">
                        Reset
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Teacher List -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Teacher List</h2>
                <p class="text-sm text-gray-500">
                    Total: {{ $teachers->total() }} teachers
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Teacher</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Subject</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Classes</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">Gender</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($teachers as $t)
                        @php
                            $initials = strtoupper(substr($t->first_name, 0, 1) . substr($t->last_name, 0, 1));
                            $teacherSubject = $subjects->firstWhere('id', $t->subject_id);
                        @endphp

                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                            <!-- Teacher -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                        {{ $initials }}
                                    </div>

                                    <div>
                                        <p class="font-bold text-gray-900">
                                            {{ $t->first_name }} {{ $t->last_name }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Teacher ID: #{{ $t->id }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ optional($t->user)->email ?? '—' }}
                            </td>

                            <!-- Subject -->
                            <td class="px-6 py-4">
                                @if($teacherSubject)
                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                                        {{ $teacherSubject->name }}
                                    </span>
                                @else
                                    <span class="text-gray-400">No subject</span>
                                @endif
                            </td>

                            <!-- Classes -->
                            <td class="px-6 py-4">
                                @if($t->classes && $t->classes->count())
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($t->classes as $class)
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                                {{ $class->name }}
                                                @if($class->grade_level)
                                                    - {{ $class->grade_level }}
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400">No classes</span>
                                @endif
                            </td>

                            <!-- Gender -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $t->gender ?? '—' }}
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.teachers.show', $t->id) }}"
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-semibold">
                                        View
                                    </a>

                                    <a href="{{ route('admin.teachers.edit', $t->id) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.teachers.destroy', $t->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this teacher?');">
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
                            <td colspan="6" class="px-6 py-12 text-center">
                                <h3 class="text-lg font-bold text-gray-800">No teachers found</h3>
                                <p class="text-gray-500 mt-1">
                                    Try searching another name, email, or subject.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $teachers->links() }}
    </div>

</div>
@endsection
