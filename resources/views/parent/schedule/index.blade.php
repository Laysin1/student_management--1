@extends('layouts.parent')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Class Schedule
            </h1>

            <p class="text-gray-600 mt-1">
                View your child’s class schedule.
            </p>
        </div>

        @php
            $parent = DB::table('parent_users')
                ->where('user_id', Auth::id())
                ->first();

            if (!$parent) {
                abort(403, 'Parent profile not found');
            }

            $children = DB::table('parent_student')
                ->join('students', 'parent_student.student_id', '=', 'students.id')
                ->leftJoin('school_classes', 'students.class_id', '=', 'school_classes.id')
                ->where('parent_student.parent_user_id', $parent->id)
                ->select(
                    'students.id as student_id',
                    'students.first_name',
                    'students.last_name',
                    'students.class_id',
                    'school_classes.name as class_name',
                    'school_classes.grade_level'
                )
                ->get();

            $selectedStudentId = request('student_id', $children->first()->student_id ?? null);

            $selectedChild = $children->firstWhere('student_id', (int)$selectedStudentId);

            $schedule = null;

            if ($selectedChild && $selectedChild->class_id) {
                $schedule = DB::table('schedules')
                    ->where('class_id', $selectedChild->class_id)
                    ->whereNotNull('photo_path')
                    ->first();
            }
        @endphp

        <!-- Child Selector -->
        <div class="bg-white rounded-xl shadow border border-gray-100 p-6 mb-6">

            <form method="GET"
                  class="flex flex-col md:flex-row md:items-center gap-4">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Select Child
                    </h2>

                    <p class="text-sm text-gray-500">
                        Choose which child’s schedule to view.
                    </p>
                </div>

                <select name="student_id"
                        onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-4 py-2.5 w-full md:w-80 font-semibold">

                    @foreach($children as $child)
                        <option value="{{ $child->student_id }}"
                            {{ (int)$selectedStudentId === (int)$child->student_id ? 'selected' : '' }}>

                            {{ $child->first_name }}
                            {{ $child->last_name }}

                            - {{ $child->grade_level ?? 'N/A' }}

                        </option>
                    @endforeach
                </select>

            </form>
        </div>

        @if($selectedChild)

            <!-- Child Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">

                <p class="text-sm text-blue-700 font-semibold">
                    Viewing Schedule For
                </p>

                <h2 class="text-xl font-bold text-blue-900">
                    {{ $selectedChild->first_name }}
                    {{ $selectedChild->last_name }}
                </h2>

                <p class="text-sm text-blue-800 mt-1">
                     {{ $selectedChild->grade_level ?? 'N/A' }}

                    @if($selectedChild->class_name)
                        - {{ $selectedChild->class_name }}
                    @endif
                </p>

            </div>

            <!-- Schedule Picture -->
            <div class="bg-white rounded-2xl shadow border border-gray-100 overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-900">
                        Schedule Picture
                    </h3>
                </div>

                <div class="p-6">

                    @if($schedule && $schedule->photo_path)

                        <a href="{{ asset('storage/' . $schedule->photo_path) }}"
                           target="_blank">

                            <img
                                src="{{ asset('storage/' . $schedule->photo_path) }}"
                                alt="Schedule"
                                class="w-full rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition"
                            >

                        </a>

                    @else

                        <div class="text-center py-16">

                            <p class="text-lg font-bold text-gray-900">
                                No schedule uploaded
                            </p>

                            <p class="text-sm text-gray-500 mt-2">
                                The class schedule image has not been uploaded yet.
                            </p>

                        </div>

                    @endif

                </div>
            </div>

        @endif

    </div>
</div>
@endsection
