@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('teacher.classes.show', $student->class->id) }}" class="text-blue-600 hover:text-blue-800">← Back to Class</a>
            <h2 class="font-semibold text-2xl text-gray-800">Student Profile</h2>
        </div>

        <!-- Student Profile Card -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Profile Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-blue-600">
                        {{ substr($student->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold">{{ $student->user->name }}</h3>
                        <p class="text-blue-100">Student ID: {{ $student->student_id }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="p-8">
                <!-- Personal Information -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">Personal Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-lg text-gray-900">{{ $student->user->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Email</label>
                            <p class="text-lg text-gray-900">{{ $student->user->email }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Contact Number</label>
                            <p class="text-lg text-gray-900">{{ $student->user->contact_number ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">First Name</label>
                            <p class="text-lg text-gray-900">{{ $student->user->first_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Last Name</label>
                            <p class="text-lg text-gray-900">{{ $student->user->last_name ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">Academic Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Class</label>
                            <p class="text-lg text-gray-900">{{ $student->class->name ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Grade Level</label>
                            <p class="text-lg text-gray-900">{{ $student->class->grade_level ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Section</label>
                            <p class="text-lg text-gray-900">{{ $student->class->section ?? 'Not assigned' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Status</label>
                            <p class="text-lg">
                                <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $student->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($student->status ?? 'Active') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">Additional Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                            <p class="text-lg text-gray-900">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('M d, Y') : 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Gender</label>
                            <p class="text-lg text-gray-900">{{ ucfirst($student->gender ?? 'Not provided') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Address</label>
                            <p class="text-lg text-gray-900">{{ $student->address ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Phone</label>
                            <p class="text-lg text-gray-900">{{ $student->phone ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="mb-8">
                    <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">Guardian Information</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Guardian Name</label>
                            <p class="text-lg text-gray-900">{{ $student->guardian_name ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Guardian Contact</label>
                            <p class="text-lg text-gray-900">{{ $student->guardian_contact ?? 'Not provided' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-500">Guardian Address</label>
                            <p class="text-lg text-gray-900">{{ $student->guardian_address ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-6 border-t">
                    <a href="{{ route('teacher.students.edit', $student->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-6 rounded">
                        Edit Student
                    </a>
                    <form method="POST" action="{{ route('teacher.students.destroy', $student->id) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded" onclick="return confirm('Are you sure you want to delete this student?')">
                            Delete Student
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
