@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-4xl">

    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Add Schedule</h1>
            <p class="text-gray-500 mt-1">
                Upload a schedule image for a class or teacher.
            </p>
        </div>

        <a href="{{ route('admin.schedules.index') }}"
           class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-2 rounded-xl font-semibold">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6">
            <h3 class="font-bold mb-2">Please fix these errors:</h3>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.schedules.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 space-y-6">

        @csrf

        <div>
            <label class="font-semibold text-gray-800 mb-2 block">Schedule Title</label>
            <input type="text"
                   name="title"
                   value="{{ old('title') }}"
                   placeholder="Example: Grade 11 Weekly Schedule"
                   class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <p class="text-xs text-gray-400 mt-1">Optional. Leave empty if you do not want a title.</p>
        </div>

        <div>
            <label class="font-semibold text-gray-800 mb-3 block">
                Schedule For <span class="text-red-500">*</span>
            </label>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <label class="border rounded-2xl p-5 cursor-pointer hover:border-blue-500 transition flex items-start gap-3">
                    <input type="radio"
                           name="type"
                           value="class"
                           class="mt-1"
                           {{ old('type', 'class') === 'class' ? 'checked' : '' }}>
                    <div>
                        <h3 class="font-bold text-gray-900">Class Schedule</h3>
                        <p class="text-sm text-gray-500">Upload a schedule for one class.</p>
                    </div>
                </label>

                <label class="border rounded-2xl p-5 cursor-pointer hover:border-purple-500 transition flex items-start gap-3">
                    <input type="radio"
                           name="type"
                           value="teacher"
                           class="mt-1"
                           {{ old('type') === 'teacher' ? 'checked' : '' }}>
                    <div>
                        <h3 class="font-bold text-gray-900">Teacher Schedule</h3>
                        <p class="text-sm text-gray-500">Upload a schedule for one teacher.</p>
                    </div>
                </label>
            </div>
        </div>

        <div id="classSelect">
            <label class="font-semibold text-gray-800 mb-2 block">
                Select Class <span class="text-red-500">*</span>
            </label>

            <input list="class_list"
                   id="class_search"
                   placeholder="Search class..."
                   class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

            <input type="hidden" name="class_id" id="class_id" value="{{ old('class_id') }}">

            <datalist id="class_list">
                @foreach(($classes ?? []) as $class)
                    <option data-id="{{ $class->id }}"
                            value="{{ $class->name }}{{ $class->grade_level ? ' (' . $class->grade_level . ')' : '' }}">
                    </option>
                @endforeach
            </datalist>
        </div>

        <div id="teacherSelect" class="hidden">
            <label class="font-semibold text-gray-800 mb-2 block">
                Select Teacher <span class="text-red-500">*</span>
            </label>

            <input list="teacher_list"
                   id="teacher_search"
                   placeholder="Search teacher..."
                   disabled
                   class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-purple-500 focus:border-purple-500">

            <input type="hidden" name="teacher_id" id="teacher_id" value="{{ old('teacher_id') }}" disabled>

            <datalist id="teacher_list">
                @foreach(($teachers ?? []) as $teacher)
                    <option data-id="{{ $teacher->id }}"
                            value="{{ $teacher->first_name }} {{ $teacher->last_name }}{{ optional($teacher->subject)->name ? ' — ' . optional($teacher->subject)->name : '' }}">
                    </option>
                @endforeach
            </datalist>
        </div>

        <div>
            <label class="font-semibold text-gray-800 mb-2 block">
                Schedule Image <span class="text-red-500">*</span>
            </label>

            <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 bg-gray-50">
                <input type="file"
                       name="photo"
                       id="photo"
                       accept="image/*"
                       required
                       class="block w-full text-sm text-gray-600">

                <p class="text-xs text-gray-400 mt-2">
                    Accepted files: JPG, PNG, JPEG. Maximum size: 4 MB.
                </p>
            </div>
        </div>

        <div id="previewBox" class="hidden">
            <label class="font-semibold text-gray-800 mb-2 block">Image Preview</label>
            <img id="imagePreview"
                 src=""
                 alt="Schedule Preview"
                 class="w-full max-h-[400px] object-contain border rounded-xl bg-gray-50">
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t">
            <a href="{{ route('admin.schedules.index') }}"
               class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-xl font-semibold">
                Cancel
            </a>

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold">
                Save Schedule
            </button>
        </div>
    </form>
</div>

<script>
    const typeInputs = document.querySelectorAll('input[name="type"]');

    const classBox = document.getElementById('classSelect');
    const teacherBox = document.getElementById('teacherSelect');

    const classSearch = document.getElementById('class_search');
    const teacherSearch = document.getElementById('teacher_search');

    const classIdInput = document.getElementById('class_id');
    const teacherIdInput = document.getElementById('teacher_id');

    function setHiddenId(searchInput, hiddenInput, listId) {
        const options = document.querySelectorAll(`#${listId} option`);
        hiddenInput.value = '';

        options.forEach(option => {
            if (option.value === searchInput.value) {
                hiddenInput.value = option.dataset.id;
            }
        });
    }

    classSearch.addEventListener('input', function () {
        setHiddenId(classSearch, classIdInput, 'class_list');
    });

    teacherSearch.addEventListener('input', function () {
        setHiddenId(teacherSearch, teacherIdInput, 'teacher_list');
    });

    function updateScheduleType() {
        const selectedType = document.querySelector('input[name="type"]:checked').value;

        if (selectedType === 'class') {
            classBox.classList.remove('hidden');
            teacherBox.classList.add('hidden');

            classSearch.disabled = false;
            classIdInput.disabled = false;

            teacherSearch.disabled = true;
            teacherIdInput.disabled = true;

            teacherSearch.value = '';
            teacherIdInput.value = '';
        } else {
            teacherBox.classList.remove('hidden');
            classBox.classList.add('hidden');

            teacherSearch.disabled = false;
            teacherIdInput.disabled = false;

            classSearch.disabled = true;
            classIdInput.disabled = true;

            classSearch.value = '';
            classIdInput.value = '';
        }
    }

    typeInputs.forEach(input => {
        input.addEventListener('change', updateScheduleType);
    });

    updateScheduleType();

    const photoInput = document.getElementById('photo');
    const previewBox = document.getElementById('previewBox');
    const imagePreview = document.getElementById('imagePreview');

    photoInput.addEventListener('change', function () {
        const file = this.files[0];

        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            previewBox.classList.remove('hidden');
        } else {
            imagePreview.src = '';
            previewBox.classList.add('hidden');
        }
    });
</script>
@endsection
