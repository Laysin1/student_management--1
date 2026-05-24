@extends('layouts.admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <h1 class="text-3xl font-bold text-gray-900 mb-6">
            Create Parent Account
        </h1>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6">

            <form action="{{ route('admin.parents.store') }}" method="POST">

                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            First Name
                        </label>

                        <input type="text"
                               name="first_name"
                               value="{{ old('first_name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name
                        </label>

                        <input type="text"
                               name="last_name"
                               value="{{ old('last_name') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Phone
                        </label>

                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Occupation
                        </label>

                        <input type="text"
                               name="occupation"
                               value="{{ old('occupation') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>

                        <input type="password"
                               name="password"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>

                        <input type="password"
                               name="password_confirmation"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                </div>

                <!-- Student Search -->

                <div class="mt-6">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Link Students (Optional)
                    </label>

                    <div class="flex gap-2">

                        <input list="student_list"
                               id="student_search"
                               placeholder="Search student by name or ID..."
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">

                        <button type="button"
                                id="add_student_btn"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                            Add
                        </button>

                    </div>

                    <datalist id="student_list">
                        @foreach($students as $student)
                            <option
                                data-id="{{ $student->id }}"
                                value="{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})">
                            </option>
                        @endforeach
                    </datalist>

                    <!-- Selected students -->

                    <div id="selected_students_box"
                         class="mt-4 flex flex-wrap gap-2">
                    </div>

                    <!-- Hidden inputs -->

                    <div id="selected_students_inputs"></div>

                    <p class="text-xs text-gray-500 mt-2">
                        Search student name or ID and click Add.
                    </p>

                </div>

                <div class="mt-8 flex gap-4">

                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                        Create Parent
                    </button>

                    <a href="{{ route('admin.parents.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>
</div>

<script>

const studentSearch = document.getElementById('student_search');
const addStudentBtn = document.getElementById('add_student_btn');

const selectedStudentsBox =
document.getElementById('selected_students_box');

const selectedStudentsInputs =
document.getElementById('selected_students_inputs');

let selectedStudents = [];

function findStudentIdByText(text){

    const options =
    document.querySelectorAll('#student_list option');

    for(const option of options){

        if(option.value===text){
            return option.dataset.id;
        }

    }

    return null;
}

addStudentBtn.addEventListener('click',function(){

    const studentText =
    studentSearch.value.trim();

    const studentId =
    findStudentIdByText(studentText);

    if(!studentId){

        alert('Please select a valid student');

        return;
    }

    if(selectedStudents.includes(studentId)){

        alert('Student already added');

        return;
    }

    selectedStudents.push(studentId);

    const badge =
    document.createElement('div');

    badge.className =
    'flex items-center gap-2 bg-blue-100 text-blue-700 px-3 py-2 rounded-full text-sm font-semibold';

    badge.dataset.studentId =
    studentId;

    badge.innerHTML=`
        <span>${studentText}</span>
        <button
            type="button"
            class="remove-btn text-red-500 font-bold">
            ×
        </button>
    `;

    selectedStudentsBox.appendChild(badge);

    const input =
    document.createElement('input');

    input.type='hidden';
    input.name='student_ids[]';
    input.value=studentId;
    input.dataset.studentId=studentId;

    selectedStudentsInputs.appendChild(input);

    studentSearch.value='';

});

selectedStudentsBox.addEventListener('click',function(e){

    if(e.target.classList.contains('remove-btn')){

        const badge =
        e.target.closest('[data-student-id]');

        const studentId =
        badge.dataset.studentId;

        selectedStudents =
        selectedStudents.filter(
            id=>id!==studentId
        );

        badge.remove();

        const input =
        selectedStudentsInputs.querySelector(
            `input[data-student-id="${studentId}"]`
        );

        if(input){
            input.remove();
        }

    }

});

</script>

@endsection
