@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Classes</h1>
                <p class="mt-2 text-blue-100">
                    Manage school grades, class sections, and student groups.
                </p>
            </div>

            <a href="{{ route('admin.classes.create') }}"
               class="bg-white text-blue-700 hover:bg-blue-50 px-5 py-3 rounded-xl font-semibold shadow text-center">
                + Add Class
            </a>
        </div>
    </div>

    @php
        $data = $gradeSummary ?? collect();
        $totalGrades = $data->count();
        $totalClasses = $data->sum('classes_count');
    @endphp

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500">Total Grades</p>
            <h2 class="mt-2 text-3xl font-bold text-blue-600">
                {{ $totalGrades }}
            </h2>
            <p class="mt-2 text-xs text-gray-400">
                Grade levels available
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <p class="text-sm text-gray-500">Total Classes</p>
            <h2 class="mt-2 text-3xl font-bold text-green-600">
                {{ $totalClasses }}
            </h2>
            <p class="mt-2 text-xs text-gray-400">
                Class sections created
            </p>
        </div>

    </div>

    @if($data->isEmpty())

        <!-- Empty State -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">

            <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 flex items-center justify-center text-3xl mb-4">
                🏫
            </div>

            <h2 class="text-2xl font-bold text-gray-900">
                No Classes Available
            </h2>

            <p class="text-gray-500 mt-2">
                Start by creating your first class.
            </p>

            <a href="{{ route('admin.classes.create') }}"
               class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-xl font-semibold">
                + Add Class
            </a>

        </div>

    @else

        <!-- Class Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">

            @foreach($data as $grade)

                @php
                    $sectionsText = implode(', ', $grade['sections']);
                    $firstId = $grade['first_id'] ?? null;
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">

                    <!-- Card Top -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 p-6 text-white">

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-blue-100 text-sm">Grade Level</p>

                                <h2 class="text-2xl font-bold mt-1">
                                    {{ $grade['grade_level'] }}
                                </h2>
                            </div>

                            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center text-2xl">
                                🏫
                            </div>
                        </div>

                    </div>

                    <!-- Card Body -->
                    <div class="p-6">

                        <div class="grid grid-cols-2 gap-4 mb-5">

                            <div class="bg-blue-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-blue-600 font-semibold">
                                    Classes
                                </p>

                                <h3 class="text-2xl font-bold text-blue-700 mt-1">
                                    {{ $grade['classes_count'] }}
                                </h3>
                            </div>

                            <div class="bg-green-50 rounded-xl p-4 text-center">
                                <p class="text-xs text-green-600 font-semibold">
                                    Sections
                                </p>

                                <h3 class="text-2xl font-bold text-green-700 mt-1">
                                    {{ count($grade['sections']) }}
                                </h3>
                            </div>

                        </div>

                        <div class="mb-6">
                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                Sections
                            </p>

                            @if(!empty($grade['sections']))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($grade['sections'] as $section)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">
                                            {{ $section }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-400 text-sm">No sections</p>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-gray-100">

                            @if($firstId)

                                <div class="grid grid-cols-3 gap-2">

                                    <a href="{{ route('admin.students.index', ['class_id' => $firstId]) }}"
                                       class="text-center bg-gray-100 hover:bg-gray-200 text-gray-800 px-3 py-2 rounded-xl text-sm font-semibold">
                                        View
                                    </a>

                                    <a href="{{ route('admin.classes.edit', $firstId) }}"
                                       class="text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-xl text-sm font-semibold">
                                        Edit
                                    </a>

                                    <a href="{{ route('admin.classes.delete-list') }}"
                                       class="text-center bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-xl text-sm font-semibold">
                                        Delete
                                    </a>

                                </div>

                            @else

                                <a href="{{ route('admin.classes.create') }}"
                                   class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-semibold">
                                    Add Class
                                </a>

                            @endif

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    @endif

</div>
@endsection
