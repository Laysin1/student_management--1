@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Edit Class</h1>
                <p class="mt-2 text-blue-100">
                    Update class name and grade level.
                </p>
            </div>

            <a href="{{ route('admin.classes.index') }}"
               class="bg-white text-blue-700 hover:bg-blue-50 px-5 py-3 rounded-xl font-semibold text-center">
                Back to Classes
            </a>
        </div>
    </div>

    <!-- Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6">
            <h3 class="font-bold mb-2">Please fix these errors:</h3>

            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Current Class Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($class->name, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $class->name }}
                </h2>

                <p class="text-gray-500">
                    {{ $class->grade_level }}
                </p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form action="{{ route('admin.classes.update', $class->id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">

        @csrf
        @method('PUT')

        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Class Information
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Edit the class name and grade.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Class Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name', $class->name) }}"
                           placeholder="Example: Class A"
                           required
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Grade Level <span class="text-red-500">*</span>
                    </label>

                    <select name="grade_level"
                            required
                            class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach(($grades ?? ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12']) as $grade)
                            <option value="{{ $grade }}"
                                {{ old('grade_level', $class->grade_level) === $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 pt-6 border-t">

            <a href="{{ route('admin.classes.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-semibold">
                Cancel
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold">
                Save Changes
            </button>

        </div>

    </form>

</div>
@endsection
