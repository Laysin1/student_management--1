@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-6 py-8 max-w-5xl">

    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl p-8 text-white shadow mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Edit Teacher</h1>
                <p class="mt-2 text-blue-100">
                    Update teacher account, subject, and assigned classes.
                </p>
            </div>

            <a href="{{ route('admin.teachers.index') }}"
               class="bg-white text-blue-700 hover:bg-blue-50 px-5 py-3 rounded-xl font-semibold text-center">
                Back to Teachers
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

    <!-- Profile Summary -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold">
                {{ strtoupper(substr($teacher->first_name, 0, 1) . substr($teacher->last_name, 0, 1)) }}
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $teacher->first_name }} {{ $teacher->last_name }}
                </h2>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="teacherForm"
          action="{{ route('admin.teachers.update', $teacher->id) }}"
          method="POST"
          class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">

        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div>
            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Personal Information
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Update the teacher’s basic information.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        First Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="first_name"
                           value="{{ old('first_name', $teacher->first_name) }}"
                           placeholder="Example: John"
                           required
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Last Name <span class="text-red-500">*</span>
                    </label>

                    <input type="text"
                           name="last_name"
                           value="{{ old('last_name', $teacher->last_name) }}"
                           placeholder="Example: Doe"
                           required
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Gender
                    </label>

                    <select name="gender"
                            class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select gender</option>
                        <option value="Male" {{ old('gender', $teacher->gender) === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ old('gender', $teacher->gender) === 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ old('gender', $teacher->gender) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Phone Number
                    </label>

                    <input type="tel"
                           name="phone_number"
                           value="{{ old('phone_number', $teacher->phone_number) }}"
                           placeholder="0 000 000 00"
                           pattern="[+0-9\s\-()]{7,}"
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <p class="text-xs text-gray-400 mt-1">
                        Allowed: digits, spaces, dashes, parentheses, and +
                    </p>
                </div>

            </div>
        </div>

        <!-- Account Information -->
        <div class="border-t pt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Account Information
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Update login email or change password.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Email <span class="text-red-500">*</span>
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email', optional($teacher->user)->email) }}"
                           placeholder="teacher@example.com"
                           required
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        New Password
                    </label>

                    <input type="password"
                           name="password"
                           placeholder="Leave blank to keep current password"
                           class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    <p class="text-xs text-gray-400 mt-1">
                        Leave empty if you do not want to change the password.
                    </p>
                </div>

            </div>
        </div>

        <!-- Teaching Information -->
        <div class="border-t pt-8">
            <h2 class="text-xl font-bold text-gray-900 mb-1">
                Teaching Information
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Update subject and class assignments.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <!-- Subject -->
                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Subject
                    </label>

                    <select name="subject_id"
                            class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select subject</option>

                        @foreach(($subjects ?? []) as $sub)
                            <option value="{{ $sub->id }}"
                                {{ (string) old('subject_id', $teacher->subject_id) === (string) $sub->id ? 'selected' : '' }}>
                                {{ $sub->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Searchable Add Class -->
                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">
                        Add Class
                    </label>

                    <div class="flex gap-2">
                        <input list="class_list"
                               id="class_search"
                               placeholder="Search class..."
                               class="border border-gray-300 rounded-xl px-4 py-3 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <button type="button"
                                id="add_class_btn"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold">
                            Add
                        </button>
                    </div>

                    <datalist id="class_list">
                        @foreach($classes ?? [] as $class)
                            <option
                                data-id="{{ $class->id }}"
                                value="{{ $class->name }}{{ $class->grade_level ? ' (' . $class->grade_level . ')' : '' }}">
                            </option>
                        @endforeach
                    </datalist>
                </div>

            </div>

            <!-- Selected Classes -->
            <div class="mt-5">
                <label class="font-semibold text-gray-800 mb-2 block">
                    Selected Classes
                </label>

                <div id="selected_classes_box"
                     class="min-h-[60px] border border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50 flex flex-wrap gap-2">

                    <span id="emptyClassText" class="text-sm text-gray-400">
                        No classes selected.
                    </span>

                </div>

                <div id="selected_classes_inputs"></div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 pt-6 border-t">

            <a href="{{ route('admin.teachers.index') }}"
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

<script>
    const classSearch = document.getElementById('class_search');
    const addClassBtn = document.getElementById('add_class_btn');
    const selectedBox = document.getElementById('selected_classes_box');
    const selectedInputs = document.getElementById('selected_classes_inputs');
    const emptyClassText = document.getElementById('emptyClassText');

    let selectedClasses = [];

    const classMap = {};

    document.querySelectorAll('#class_list option').forEach(option => {
        if (option.dataset.id) {
            classMap[option.dataset.id] = option.value;
        }
    });

    @php
        $teacherClassIds = $teacher->classes ? $teacher->classes->pluck('id')->toArray() : [];
    @endphp

    const initialClassIds = @json($teacherClassIds);

    function updateEmptyText() {
        if (selectedClasses.length === 0) {
            emptyClassText.classList.remove('hidden');
        } else {
            emptyClassText.classList.add('hidden');
        }
    }

    function findClassId(text) {
        const options = document.querySelectorAll('#class_list option');

        for (const option of options) {
            if (option.value === text) {
                return option.dataset.id;
            }
        }

        return null;
    }

    function displayClass(classId, className) {
        if (!className) {
            className = 'Class ' + classId;
        }

        const badge = document.createElement('div');

        badge.className = "flex items-center gap-2 bg-blue-100 text-blue-700 px-3 py-2 rounded-full text-sm font-semibold";
        badge.dataset.classId = classId;

        badge.innerHTML = `
            <span>${className}</span>
            <button type="button" class="remove-class-btn text-red-500 hover:text-red-700 font-bold">
                ×
            </button>
        `;

        selectedBox.appendChild(badge);

        const input = document.createElement('input');
        input.type = "hidden";
        input.name = "class_ids[]";
        input.value = classId;
        input.dataset.classId = classId;

        selectedInputs.appendChild(input);

        updateEmptyText();
    }

    initialClassIds.forEach(function(classId) {
        if (!classId) return;

        const id = String(classId);

        if (!selectedClasses.includes(id)) {
            selectedClasses.push(id);
            displayClass(id, classMap[id] || 'Class ' + id);
        }
    });

    addClassBtn.addEventListener('click', function(e) {
        e.preventDefault();

        const classText = classSearch.value.trim();
        const classId = findClassId(classText);

        if (!classId) {
            alert('Please select a valid class from the list');
            return;
        }

        if (selectedClasses.includes(String(classId))) {
            alert('This class is already added');
            return;
        }

        selectedClasses.push(String(classId));
        displayClass(String(classId), classText);

        classSearch.value = "";
        updateEmptyText();
    });

    selectedBox.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-class-btn')) {
            e.preventDefault();

            const badge = e.target.closest('div[data-class-id]');
            const classId = String(badge.dataset.classId);

            selectedClasses = selectedClasses.filter(id => id !== classId);
            badge.remove();

            const input = selectedInputs.querySelector(`input[data-class-id="${classId}"]`);

            if (input) {
                input.remove();
            }

            updateEmptyText();
        }
    });

    updateEmptyText();
</script>
@endsection
