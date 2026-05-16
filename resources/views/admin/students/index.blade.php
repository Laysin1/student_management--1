@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-bold">
                    Students
                </h1>

                <p class="mt-2 text-blue-100">
                    Manage student accounts, classes, and information.
                </p>
            </div>

            <a href="{{ route('students.create') }}"
               class="bg-white text-blue-700 hover:bg-blue-50 px-5 py-3 rounded-xl font-semibold shadow text-center">
                + Add Student
            </a>

        </div>

    </div>

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

        <form method="GET"
              action="{{ route('students.index') }}"
              class="grid grid-cols-1 md:grid-cols-4 gap-4">

            <!-- Search -->
            <div class="md:col-span-2">

                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                    Search Student
                </label>

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name or email..."
                       class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            </div>

            <!-- Class -->
            <div>

                <label class="text-sm font-semibold text-gray-700 mb-2 block">
                    Class
                </label>

                <select name="class_id"
                        onchange="this.form.submit()"
                        class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">
                        All Classes
                    </option>

                    @foreach($classes as $cls)

                        <option value="{{ $cls->id }}"
                            {{ (string) request('class_id') === (string) $cls->id ? 'selected' : '' }}>

                            {{ $cls->name }}
                            @if($cls->grade_level)
                                ({{ $cls->grade_level }})
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>

            <!-- Actions -->
            <div class="flex items-end gap-2">

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-semibold">
                    Search
                </button>

                @if(request('search') || request('class_id'))

                    <a href="{{ route('students.index') }}"
                       class="w-full text-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-3 rounded-xl font-semibold">
                        Reset
                    </a>

                @endif

            </div>

        </form>

    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <!-- Card Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

            <div>
                <h2 class="text-xl font-bold text-gray-900">
                    Student List
                </h2>

                <p class="text-sm text-gray-500">
                    Total: {{ $students->total() }} students
                </p>
            </div>

        </div>

        <!-- Table -->
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-gray-50 border-b border-gray-100">

                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Student
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Student ID
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Email
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Class
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Gender
                        </th>

                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-600">
                            Age
                        </th>

                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-600">
                            Actions
                        </th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($students as $student)

                        @php
                            $initials = strtoupper(
                                substr($student->first_name, 0, 1) .
                                substr($student->last_name, 0, 1)
                            );
                        @endphp

                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition">

                            <!-- Student -->
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold">
                                        {{ $initials }}
                                    </div>

                                    <div>
                                        <p class="font-bold text-gray-900">
                                            {{ $student->first_name }}
                                            {{ $student->last_name }}
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Student Profile
                                        </p>
                                    </div>

                                </div>

                            </td>

                            <!-- Student ID -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $student->student_id ?? '—' }}
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $student->user->email ?? '—' }}
                            </td>

                            <!-- Class -->
                            <td class="px-6 py-4">

                                @if($student->class)

                                    <div class="flex flex-wrap gap-2">

                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                            {{ $student->class->name }}
                                        </span>

                                        @if($student->class->grade_level)

                                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                                {{ $student->class->grade_level }}
                                            </span>

                                        @endif

                                    </div>

                                @else

                                    <span class="text-gray-400">
                                        No Class
                                    </span>

                                @endif

                            </td>

                            <!-- Gender -->
                            <td class="px-6 py-4 text-gray-700">
                                {{ $student->gender ?? '—' }}
                            </td>

                            <!-- Age -->
                            <td class="px-6 py-4 text-gray-700">

                                @if($student->date_of_birth)

                                    {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}

                                @else

                                    —

                                @endif

                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">

                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('students.show', $student->id) }}"
                                       class="bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-lg text-sm font-semibold">
                                        View
                                    </a>

                                    <a href="{{ route('students.edit', $student->id) }}"
                                       class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">
                                        Edit
                                    </a>

                                    <form action="{{ route('students.destroy', $student->id) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Delete this student?');">

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

                            <td colspan="7"
                                class="px-6 py-12 text-center">

                                <div class="flex flex-col items-center">

                                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center text-3xl mb-4">
                                        🎓
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-800">
                                        No Students Found
                                    </h3>

                                    <p class="text-gray-500 mt-1">
                                        Try another search or add a new student.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $students->links() }}
    </div>

</div>
@endsection
