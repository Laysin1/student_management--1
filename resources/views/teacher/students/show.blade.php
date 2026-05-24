@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('teacher.classes.show', $student->class->id) }}"
               class="text-blue-600 hover:text-blue-800">
                ← Back to Class
            </a>

            <h2 class="font-semibold text-2xl text-gray-800">
                Student Profile
            </h2>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">

            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8 text-white">
                <div class="flex items-center gap-6">

                    @if($student->profile_photo_path)
                        <img src="{{ asset('storage/'.$student->profile_photo_path) }}"
                             class="w-24 h-24 rounded-full object-cover border-4 border-white">
                    @else
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-blue-600">
                            {{ strtoupper(substr($student->first_name, 0, 1)) }}
                        </div>
                    @endif

                    <div>
                        <h3 class="text-3xl font-bold">
                            {{ $student->first_name }} {{ $student->last_name }}
                        </h3>

                        <p class="text-blue-100">
                            Student ID: {{ $student->student_id ?? 'Not provided' }}
                        </p>
                    </div>

                </div>
            </div>

            <div class="p-8">

                <h4 class="text-xl font-semibold text-gray-800 mb-4 border-b-2 border-blue-500 pb-2">
                    Student Information
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="text-sm font-medium text-gray-500">Student ID</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->student_id ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Email</label>
                        <p class="text-lg text-gray-900">
                            {{ optional($student->user)->email ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">First Name</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->first_name ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Last Name</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->last_name ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Date of Birth</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d') : 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->phone_number ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Class</label>
                        <p class="text-lg text-gray-900">
                            {{ optional($student->class)->name ?? 'Not provided' }}

                            @if(optional($student->class)->grade_level)
                                ({{ $student->class->grade_level }})
                            @endif
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Gender</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->gender ?? 'Not provided' }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-gray-500">Parent Number</label>
                        <p class="text-lg text-gray-900">
                            {{ $student->parent_number ?? 'Not provided' }}
                        </p>
                    </div>

                </div>

                <div class="flex gap-4 pt-6 border-t mt-8">
                    <a href="{{ route('teacher.classes.show', $student->class->id) }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                        Back
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
