@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-5xl">

    <!-- Header Actions -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.teachers.index') }}"
           class="inline-flex items-center text-gray-600 hover:text-blue-600 font-semibold">
            ← Back
        </a>

        <div class="flex gap-2">
            <a href="{{ route('admin.teachers.edit', $teacher->id) }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl font-semibold">
                Edit
            </a>

            <form action="{{ route('admin.teachers.destroy', $teacher->id) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this teacher?');">
                @csrf
                @method('DELETE')

                <button type="submit"
                        class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-xl font-semibold">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Teacher Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">

        <!-- Banner -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 h-36 relative">

            <!-- Avatar -->
            <div class="absolute -bottom-12 left-8">
                <div class="w-24 h-24 rounded-full bg-white border-4 border-white shadow flex items-center justify-center text-4xl font-bold text-blue-700">
                    {{ strtoupper(substr($teacher->first_name, 0, 1)) }}
                </div>
            </div>

        </div>

        <!-- Profile Content -->
        <div class="pt-16 p-8">

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $teacher->first_name }} {{ $teacher->last_name }}
                    </h1>

                    <p class="text-gray-500 mt-1">
                        {{ optional($teacher->subject)->name ?? 'No subject assigned' }}
                    </p>
                </div>

                {{-- <span class="inline-block px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                    Teacher ID: #{{ $teacher->id }}
                </span> --}}
            </div>

            <!-- Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="font-semibold text-gray-900 mt-1">
                        {{ optional($teacher->user)->email ?? '—' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-sm text-gray-500">Phone</p>
                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $teacher->phone_number ?? '—' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-sm text-gray-500">Gender</p>
                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $teacher->gender ?? '—' }}
                    </p>
                </div>

                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-sm text-gray-500">Assigned Classes</p>
                    <p class="font-semibold text-gray-900 mt-1">
                        {{ $teacher->classes ? $teacher->classes->count() : 0 }}
                    </p>
                </div>

            </div>

        </div>
    </div>

    <!-- Classes Assigned -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Classes Assigned
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Classes currently connected to this teacher.
                </p>
            </div>
        </div>

        @if($teacher->classes && $teacher->classes->count())

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                @foreach($teacher->classes as $class)

                    <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md hover:border-blue-200 transition">

                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    {{ $class->name }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Grade Level: {{ $class->grade_level ?? '—' }}
                                </p>
                            </div>

                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                Class
                            </span>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Total Students</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1">
                                {{ $class->students ? $class->students->count() : 0 }}
                            </p>
                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="bg-gray-50 rounded-2xl p-8 text-center">
                <h3 class="text-lg font-bold text-gray-800">
                    No Classes Assigned
                </h3>

                <p class="text-gray-500 mt-2">
                    This teacher is not assigned to any class yet.
                </p>
            </div>

        @endif

    </div>

</div>
@endsection
