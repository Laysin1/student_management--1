@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-bold">
                    Add Class
                </h1>

                <p class="mt-2 text-blue-100">
                    Create a new class and assign a schedule.
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

            <h3 class="font-bold mb-2">
                Please fix these errors:
            </h3>

            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif

    <!-- Form -->
    <form id="classForm"
          action="{{ route('admin.classes.store') }}"
          method="POST"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">

        @csrf

        <!-- Basic Information -->
        <div>

            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Class Information
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Enter the class name and grade level.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Class Name -->
                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Class Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="name"
                           id="class_name"
                           value="{{ old('name') }}"
                           placeholder="Example: 11A-Math or Section C"
                           required
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <p class="text-xs text-gray-400 mt-1">
                        Use a unique class or section name.
                    </p>
                </div>

                <!-- Grade -->
                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Grade Level <span class="text-red-500">*</span>
                    </label>

                    <select name="grade_level"
                            id="grade_level"
                            required
                            class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="">
                            Select grade
                        </option>

                        @foreach(($grades ?? ['Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12']) as $grade)

                            <option value="{{ $grade }}"
                                {{ old('grade_level') === $grade ? 'selected' : '' }}>
                                {{ $grade }}
                            </option>

                        @endforeach

                    </select>
                </div>

            </div>

        </div>

        <!-- Schedule -->
        {{-- <div class="border-t pt-8">

            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Schedule Assignment
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Assign an existing schedule to this class.
            </p>

            <div>

                <label class="font-semibold text-gray-800 mb-2 block">
                    Select Schedule
                </label>

                <select name="schedule_id"
                        id="schedule_id"
                        class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <option value="">
                        No schedule selected
                    </option>

                    @foreach(($schedules ?? []) as $schedule)

                        <option value="{{ $schedule->id }}"
                            {{ (string) old('schedule_id') === (string) $schedule->id ? 'selected' : '' }}>

                            {{ $schedule->title ?? 'Schedule #'.$schedule->id }}

                            @if($schedule->type)
                                — {{ ucfirst($schedule->type) }}
                            @endif

                        </option>

                    @endforeach

                </select>

                <p class="text-xs text-gray-400 mt-2">
                    Optional. You can assign a schedule later if needed.
                </p>

            </div>

        </div> --}}

        <!-- Preview -->
        <div class="border-t pt-8">

            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Preview
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Preview how the class information will appear.
            </p>

            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6">

                <div class="flex items-center justify-between">

                    <div>
                        <p class="text-sm text-gray-500">
                            Class Name
                        </p>

                        <h3 id="previewClassName"
                            class="text-2xl font-bold text-gray-900 mt-1">
                            —
                        </h3>
                    </div>

                    <div class="w-14 h-14 rounded-2xl bg-blue-100 flex items-center justify-center text-2xl">
                        🏫
                    </div>

                </div>

                <div class="mt-5 grid grid-cols-2 gap-4">

                    <div class="bg-white rounded-xl p-4 border border-gray-100">
                        <p class="text-sm text-gray-500">
                            Grade
                        </p>

                        <p id="previewGrade"
                           class="font-bold text-gray-900 mt-1">
                            —
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-4 border border-gray-100">
                        <p class="text-sm text-gray-500">
                            Schedule
                        </p>

                        <p id="previewSchedule"
                           class="font-bold text-gray-900 mt-1">
                            None
                        </p>
                    </div>

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
                Create Class
            </button>

        </div>

    </form>

</div>

<script>
    const classNameInput = document.getElementById('class_name');
    const gradeInput = document.getElementById('grade_level');
    const scheduleInput = document.getElementById('schedule_id');

    const previewClassName = document.getElementById('previewClassName');
    const previewGrade = document.getElementById('previewGrade');
    const previewSchedule = document.getElementById('previewSchedule');

    function updatePreview() {

        previewClassName.textContent =
            classNameInput.value || '—';

        previewGrade.textContent =
            gradeInput.value || '—';

        const selectedSchedule =
            scheduleInput.options[scheduleInput.selectedIndex];

        previewSchedule.textContent =
            selectedSchedule && selectedSchedule.value
                ? selectedSchedule.text
                : 'None';
    }

    classNameInput.addEventListener('input', updatePreview);
    gradeInput.addEventListener('change', updatePreview);
    scheduleInput.addEventListener('change', updatePreview);

    updatePreview();
</script>
@endsection
