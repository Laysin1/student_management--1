@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-7xl">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Class</h1>
            <p class="text-gray-600 mt-1">Update class information, search students, move students, and delete class safely.</p>
        </div>

        <a href="{{ route('admin.classes.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2.5 rounded-lg font-semibold">
            ← Back
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-lg mb-6">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 space-y-6">

            <form action="{{ route('admin.classes.update', $class->id) }}"
                  method="POST"
                  class="bg-white rounded-xl shadow border border-gray-100 p-6 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Class Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="font-semibold text-gray-800 mb-2 block">Class Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $class->name) }}"
                                   required
                                   class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="font-semibold text-gray-800 mb-2 block">Grade Level</label>
                            <select name="grade_level"
                                    required
                                    class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500">
                                @foreach($grades as $grade)
                                    <option value="{{ $grade }}"
                                        {{ old('grade_level', $class->grade_level) === $grade ? 'selected' : '' }}>
                                        {{ $grade }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <a href="{{ route('admin.classes.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold">
                        Cancel
                    </a>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold">
                        Save Changes
                    </button>
                </div>
            </form>

            <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Students in this Class</h2>
            <p class="text-sm text-gray-500 mt-1">
                Search student by name, student ID, or email.
            </p>
        </div>

        <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold w-fit">
            {{ $studentCount }} students
        </span>
    </div>

    <form method="GET"
          action="{{ route('admin.classes.edit', $class->id) }}"
          class="p-5 border-b bg-gray-50 flex gap-3">
        <input type="text"
               name="search"
               value="{{ request('search') }}"
               placeholder="Search student name, ID, or email..."
               class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500">

        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
            Search
        </button>
    </form>

    <div class="overflow-x-auto max-h-[430px] overflow-y-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600">Student</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600">Student ID</th>
                    <th class="px-5 py-3 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-5 py-3 text-center font-semibold text-gray-600">Gender</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($students as $student)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3 font-semibold text-gray-900">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </td>

                        <td class="px-5 py-3 text-gray-700">
                            {{ $student->student_id ?? '—' }}
                        </td>

                        <td class="px-5 py-3 text-gray-700">
                            {{ $student->user->email ?? '—' }}
                        </td>

                        <td class="px-5 py-3 text-center text-gray-700">
                            {{ ucfirst($student->gender ?? '—') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center text-gray-500">
                            No students found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="p-5 border-t">
        {{ $students->links() }}
    </div>
</div>

        </div>

        <div class="space-y-6">

            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Class Summary</h2>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Class</span>
                        <span class="font-semibold">{{ $class->name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Grade</span>
                        <span class="font-semibold">{{ $class->grade_level }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Students</span>
                        <span class="font-semibold">{{ $studentCount }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-500">Teachers</span>
                        <span class="font-semibold">{{ $class->teachers->count() }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-gray-900">Assigned Teachers</h2>

        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
            {{ $class->teachers->count() }} teachers
        </span>
    </div>

    <div class="max-h-[360px] overflow-y-auto pr-2 space-y-3">
        @forelse($class->teachers as $teacher)
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-4">
                <p class="font-semibold text-gray-900">
                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    @php
    $subjectName = DB::table('subjects')
        ->where('id', $teacher->subject_id)
        ->value('name');
@endphp

{{ $subjectName ?? 'No subject assigned' }}
                </p>
            </div>
        @empty
            <p class="text-gray-500 text-sm">No teachers assigned.</p>
        @endforelse
    </div>
</div>

            <div class="bg-white rounded-xl shadow border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-3">Move All Students</h2>

                @if($studentCount > 0)
                    <p class="text-sm text-gray-600 mb-4">
                        Move all students from this class to another class, or remove them from this class.
                    </p>

                    <form action="{{ route('admin.classes.moveStudents', $class->id) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to move all students from this class?');"
                          class="space-y-4">
                        @csrf

                        <select name="target_class_id"
                                class="border border-gray-300 rounded-lg px-4 py-3 w-full focus:ring-2 focus:ring-blue-500">
                            <option value="">Remove from class / No class</option>
                            @foreach($availableClasses as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->name }} - {{ $item->grade_level }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit"
                                class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-lg font-semibold">
                            Move All Students
                        </button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">
                        No students to move.
                    </p>
                @endif
            </div>

            <div class="rounded-xl border p-6
                {{ $studentCount > 0 ? 'bg-gray-50 border-gray-200' : 'bg-red-50 border-red-200' }}">

                <h2 class="text-lg font-bold mb-2
                    {{ $studentCount > 0 ? 'text-gray-700' : 'text-red-800' }}">
                    Delete Class
                </h2>

                @if($studentCount > 0)
                    <p class="text-sm text-gray-600">
                        This class cannot be deleted because it still has students.
                        Move all students first, then you can delete this class.
                    </p>
                @else
                    <p class="text-sm text-red-700 mb-4">
                        This class has no students. You can delete it if it is no longer needed.
                    </p>

                    <form action="{{ route('admin.classes.destroy', $class->id) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this class? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-lg font-semibold">
                            Delete Class
                        </button>
                    </form>
                @endif
            </div>

        </div>

    </div>
</div>
@endsection
