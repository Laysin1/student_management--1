@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('teacher.classes.index') }}" class="text-blue-600 hover:text-blue-800">← Back</a>
            <h2 class="font-semibold text-2xl text-gray-800">{{ $class->name }} - {{ $class->grade_level }}</h2>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Age</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($class->students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $student->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $student->student_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $student->user->email ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                @if($student->date_of_birth)
                                    {{ \Carbon\Carbon::parse($student->date_of_birth)->age }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($student->gender ?? 'Not provided') }}</td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="{{ route('teacher.students.show', $student->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No students in this class.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
