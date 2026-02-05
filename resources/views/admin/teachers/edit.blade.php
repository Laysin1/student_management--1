@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">
    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ url()->previous() }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7M3 12h18"/>
            </svg>
            Back
        </a>
    </div>

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Teacher</h1>
    <p class="text-gray-600 mb-6">Update teacher details</p>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="teacherForm" action="{{ route('teachers.update', $teacher->id) }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf
        @method('PUT')

        <!-- Full Name Display -->
        <div class="bg-blue-50 p-4 rounded border border-blue-200 mb-4">
            <p class="text-sm text-gray-600">Full Name</p>
            <p class="text-lg font-semibold text-gray-800">{{ $teacher->first_name }} {{ $teacher->last_name }}</p>
        </div>

        <!-- First Name -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">First Name <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="first_name"
                id="first_name"
                placeholder="e.g., John"
                value="{{ old('first_name', $teacher->first_name) }}"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Last Name -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Last Name <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="last_name"
                id="last_name"
                placeholder="e.g., Doe"
                value="{{ old('last_name', $teacher->last_name) }}"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Email, Classes, Gender -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800 mb-2">Email <span class="text-red-500">*</span></label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="name@example.com"
                    value="{{ old('email', optional($teacher->user)->email) }}"
                    class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <!-- Classes selection with add button and box -->
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800 mb-2">Classes</label>
                <div class="flex gap-2">
                    <select id="class_select" class="border border-gray-300 rounded px-6 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Select class</option>
                        @foreach($classes ?? [] as $class)
                            <option value="{{ $class->id }}">
                                {{ $class->name }} ({{ $class->grade_level }})
                            </option>
                        @endforeach
                    </select>
                    <button type="button" id="add_class_btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">Add</button>
                </div>
                <div id="selected_classes_box" class="mt-3 space-y-2">
                    <!-- Selected classes will appear here -->
                </div>
                <!-- Hidden inputs for selected classes -->
                <div id="selected_classes_inputs"></div>
            </div>

            <div class="flex flex-col">
                <label class="font-semibold text-gray-800 mb-2">Gender</label>
                <select
                    name="gender"
                    id="gender"
                    class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('gender', $teacher->gender)==='Male'?'selected':'' }}>Male</option>
                    <option value="Female" {{ old('gender', $teacher->gender)==='Female'?'selected':'' }}>Female</option>
                    <option value="Other" {{ old('gender', $teacher->gender)==='Other'?'selected':'' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Password</label>
            <input
                type="password"
                name="password"
                id="password"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                placeholder="Leave blank to keep current"
            >
            <small class="text-gray-500 mt-1">Leave empty to keep existing password.</small>
        </div>

        <!-- Phone -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Phone number</label>
            <input
                type="tel"
                name="phone_number"
                id="phone"
                placeholder="+1 555 123 4567"
                value="{{ old('phone_number', $teacher->phone_number) }}"
                pattern="^[+0-9][0-9\s\-()]{6,}$"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
            >
            <small class="text-gray-500 mt-1">Allowed: digits, spaces, dashes, parentheses, +</small>
        </div>

        <!-- Subject (subject_id) -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Subject</label>
            <select
                name="subject_id"
                id="subject_id"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Select subject</option>
                @foreach(($subjects ?? []) as $sub)
                    <option value="{{ $sub->id }}" {{ (string)old('subject_id', $teacher->subject_id) === (string)$sub->id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                Save
            </button>
            <a href="{{ route('teachers.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded font-semibold">
                Cancel
            </a>
        </div>
    </form>

    <script>
        const classSelect = document.getElementById('class_select');
        const addClassBtn = document.getElementById('add_class_btn');
        const selectedBox = document.getElementById('selected_classes_box');
        const selectedInputs = document.getElementById('selected_classes_inputs');
        let selectedClasses = [];

        // Create class map from the select options
        const classMap = {};
        Array.from(classSelect.options).forEach(option => {
            if (option.value) {
                classMap[option.value] = option.text;
            }
        });

        // Load currently assigned classes
        @php
            $teacherClassIds = $teacher->classes ? $teacher->classes->pluck('id')->toArray() : [];
        @endphp

        const initialClassIds = @json($teacherClassIds);

        initialClassIds.forEach(function(classId) {
            if (!classId) return;
            selectedClasses.push(String(classId));
            displayClass(classId, classMap[classId] || 'Class ' + classId);
        });

        function displayClass(classId, className) {
            const div = document.createElement('div');
            div.className = "flex items-center gap-2 bg-blue-50 px-3 py-2 rounded border border-blue-200";
            div.dataset.classId = classId;
            div.innerHTML = `
                <span class="flex-1">${className}</span>
                <button type="button" class="remove-class-btn text-red-500 hover:text-red-700 font-bold">&times;</button>
            `;
            selectedBox.appendChild(div);

            const input = document.createElement('input');
            input.type = "hidden";
            input.name = "class_ids[]";
            input.value = classId;
            input.dataset.classId = classId;
            selectedInputs.appendChild(input);
        }

        addClassBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const classId = classSelect.value;
            const classText = classSelect.options[classSelect.selectedIndex].text;

            if (!classId) {
                alert('Please select a class');
                return;
            }

            if (selectedClasses.includes(String(classId))) {
                alert('This class is already added');
                return;
            }

            selectedClasses.push(String(classId));
            displayClass(classId, classText);
            classSelect.value = "";
        });

        // Remove class handler
        selectedBox.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-class-btn')) {
                e.preventDefault();
                const div = e.target.closest('div[data-class-id]');
                const classId = String(div.dataset.classId);
                selectedClasses = selectedClasses.filter(id => id !== classId);
                div.remove();

                // Remove hidden input
                const input = selectedInputs.querySelector(`input[data-class-id="${classId}"]`);
                if (input) input.remove();
            }
        });
    </script>
</div>
@endsection
