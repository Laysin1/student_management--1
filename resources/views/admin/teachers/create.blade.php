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

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Add Teachers</h1>
    <p class="text-gray-600 mb-6">Manually</p>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="teacherForm" action="{{ route('teachers.store') }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-5">
        @csrf

        <!-- Full Name -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">First Name <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="first_name"
                id="first_name"
                placeholder="e.g., John"
                value="{{ old('first_name') }}"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Last Name <span class="text-red-500">*</span></label>
            <input
                type="text"
                name="last_name"
                id="last_name"
                placeholder="e.g., Doe"
                value="{{ old('last_name') }}"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
        </div>

        <!-- Email, Classes, Gender (grouped row) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex flex-col">
                <label class="font-semibold text-gray-800 mb-2">Email <span class="text-red-500">*</span></label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    placeholder="name@example.com"
                    value="{{ old('email') }}"
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
                <label class="font-semibold text-gray-800 mb-2">Gender <span class="text-red-500">*</span></label>
                <select
                    name="gender"
                    id="gender"
                    class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                    required
                >
                    <option value="">Select gender</option>
                    <option value="Male" {{ old('gender')==='Male'?'selected':'' }}>Male</option>
                    <option value="Female" {{ old('gender')==='Female'?'selected':'' }}>Female</option>
                    <option value="Other" {{ old('gender')==='Other'?'selected':'' }}>Other</option>
                </select>
            </div>
        </div>

        <!-- Password -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Password <span class="text-red-500">*</span></label>
            <input
                type="password"
                name="password"
                id="password"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 mt-2"
                placeholder="Confirm password"
                required
            >
        </div>

        <!-- Phone -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Phone number <span class="text-red-500">*</span></label>
            <input
                type="tel"
                name="phone_number"
                id="phone"
                placeholder="+855 000 000 000"
                value="{{ old('phone_number') }}"
                pattern="^[+0-9][0-9\s\-()]{6,}$"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
            <small class="text-gray-500 mt-1">Allowed: digits, spaces, dashes, parentheses, +</small>
        </div>

        <!-- Subject (subject_id) -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Subject <span class="text-red-500">*</span></label>
            <select
                name="subject_id"
                id="subject_id"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
                required
            >
                <option value="">Select subject</option>
                @foreach(($subjects ?? []) as $sub)
                    <option value="{{ $sub->id }}" {{ (string)old('subject_id') === (string)$sub->id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Schedule (optional) -->
        <div class="flex flex-col">
            <label class="font-semibold text-gray-800 mb-2">Schedule</label>
            <select
                name="schedule_id"
                id="schedule_id"
                class="border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Select schedule</option>
                @foreach($schedules ?? [] as $sched)
                    <option value="{{ $sched->id }}" {{ (string)old('schedule_id') === (string)$sched->id ? 'selected' : '' }}>
                        {{ $sched->day_of_week }} {{ $sched->start_time }}-{{ $sched->end_time }}
                    </option>
                @endforeach
            </select>
            <small class="text-gray-500 mt-1">Select to associate an existing schedule.</small>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-2">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                Add
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

        addClassBtn.addEventListener('click', function() {
            const classId = classSelect.value;
            const classText = classSelect.options[classSelect.selectedIndex].text;
            if (!classId || selectedClasses.includes(classId)) return;

            selectedClasses.push(classId);

            // Add to display box
            const div = document.createElement('div');
            div.className = "flex items-center gap-2 bg-blue-50 px-3 py-1 rounded";
            div.dataset.classId = classId;
            div.innerHTML = `
                <span>${classText}</span>
                <button type="button" class="remove-class-btn text-red-500 hover:text-red-700" title="Remove">&times;</button>
            `;
            selectedBox.appendChild(div);

            // Add hidden input
            const input = document.createElement('input');
            input.type = "hidden";
            input.name = "class_ids[]";
            input.value = classId;
            input.dataset.classId = classId;
            selectedInputs.appendChild(input);

            // Reset dropdown
            classSelect.value = "";
        });

        // Remove class handler
        selectedBox.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-class-btn')) {
                const div = e.target.closest('div[data-class-id]');
                const classId = div.dataset.classId;
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
