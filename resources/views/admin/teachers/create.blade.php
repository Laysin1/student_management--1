@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-3xl">

    <!-- Header -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ url()->previous() }}"
           class="inline-flex items-center text-gray-600 hover:text-blue-600">

            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6 mr-1"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 19l-7-7 7-7M3 12h18"/>
            </svg>

            Back
        </a>
    </div>

    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
        Add Teachers
    </h1>

    <p class="text-gray-600 mb-6">
        Manually
    </p>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


<form id="teacherForm"
      action="{{ route('admin.teachers.store') }}"
      method="POST"
      class="bg-white shadow rounded-lg p-6 space-y-5">

@csrf


<!-- First Name -->

<div class="flex flex-col">

<label class="font-semibold text-gray-800 mb-2">
First Name *
</label>

<input
type="text"
name="first_name"
value="{{ old('first_name') }}"
class="border border-gray-300 rounded px-3 py-2"
required>

</div>


<!-- Last Name -->

<div class="flex flex-col">

<label class="font-semibold text-gray-800 mb-2">
Last Name *
</label>

<input
type="text"
name="last_name"
value="{{ old('last_name') }}"
class="border border-gray-300 rounded px-3 py-2"
required>

</div>


<div class="grid grid-cols-1 md:grid-cols-3 gap-4">


<!-- Email -->

<div>

<label class="font-semibold text-gray-800 mb-2 block">
Email *
</label>

<input
type="email"
name="email"
value="{{ old('email') }}"
class="border border-gray-300 rounded px-3 py-2 w-full"
required>

</div>



<!-- Searchable Classes -->

<div>

<label class="font-semibold text-gray-800 mb-2 block">
Classes
</label>

<div class="flex gap-2">

<input
list="class_list"
id="class_search"
placeholder="Search class..."
class="w-full border border-gray-300 rounded px-3 py-2">

<button
type="button"
id="add_class_btn"
class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">

Add

</button>

</div>

<datalist id="class_list">

@foreach($classes ?? [] as $class)

<option
data-id="{{ $class->id }}"
value="{{ $class->name }} ({{ $class->grade_level }})">

</option>

@endforeach

</datalist>


<div id="selected_classes_box"
     class="mt-3 flex flex-wrap gap-2">
</div>

<div id="selected_classes_inputs"></div>

</div>



<!-- Gender -->

<div>

<label class="font-semibold text-gray-800 mb-2 block">
Gender *
</label>

<select
name="gender"
class="border border-gray-300 rounded px-3 py-2 w-full"
required>

<option value="">Select gender</option>

<option value="Male"
{{ old('gender')==='Male'?'selected':'' }}>
Male
</option>

<option value="Female"
{{ old('gender')==='Female'?'selected':'' }}>
Female
</option>

<option value="Other"
{{ old('gender')==='Other'?'selected':'' }}>
Other
</option>

</select>

</div>

</div>


<!-- Password -->

<div>

<label class="font-semibold text-gray-800 mb-2 block">
Password *
</label>

<input
type="password"
name="password"
class="border border-gray-300 rounded px-3 py-2 w-full"
required>

<input
type="password"
name="password_confirmation"
placeholder="Confirm Password"
class="border border-gray-300 rounded px-3 py-2 w-full mt-2"
required>

</div>



<!-- Phone -->

<div>

<label class="font-semibold text-gray-800 mb-2 block">
Phone Number *
</label>

<input
type="tel"
name="phone_number"
value="{{ old('phone_number') }}"
class="border border-gray-300 rounded px-3 py-2 w-full"
required>

</div>



<!-- Subject -->

<div>

<label class="font-semibold text-gray-800 mb-2 block">
Subject *
</label>

<select
name="subject_id"
class="border border-gray-300 rounded px-3 py-2 w-full"
required>

<option value="">Select Subject</option>

@foreach(($subjects ?? []) as $sub)

<option
value="{{ $sub->id }}"
{{ (string)old('subject_id')===(string)$sub->id ? 'selected':'' }}>

{{ $sub->name }}

</option>

@endforeach

</select>

</div>



<div class="flex gap-3 pt-2">

<button
type="submit"
class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded">

Add

</button>

<a href="{{ route('admin.teachers.index') }}"
class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">

Cancel

</a>

</div>

</form>

</div>


<script>

const classSearch=
document.getElementById(
'class_search'
);

const addClassBtn=
document.getElementById(
'add_class_btn'
);

const selectedBox=
document.getElementById(
'selected_classes_box'
);

const selectedInputs=
document.getElementById(
'selected_classes_inputs'
);

let selectedClasses=[];


function findClassId(text){

const options=
document.querySelectorAll(
'#class_list option'
);

for(const option of options){

if(option.value===text){

return option.dataset.id;

}

}

return null;

}



addClassBtn.addEventListener(
'click',
function(){

const classText=
classSearch.value.trim();

const classId=
findClassId(classText);

if(!classId){

alert(
'Please select a valid class'
);

return;

}


if(
selectedClasses.includes(
classId
)
){

alert(
'Class already added'
);

return;

}

selectedClasses.push(
classId
);


const div=
document.createElement(
'div'
);

div.className=
'flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-full';

div.dataset.classId=
classId;

div.innerHTML=`

<span>${classText}</span>

<button
type="button"
class="remove-class-btn text-red-500 font-bold">

×

</button>
`;

selectedBox.appendChild(
div
);


const input=
document.createElement(
'input'
);

input.type='hidden';
input.name='class_ids[]';
input.value=classId;
input.dataset.classId=
classId;

selectedInputs.appendChild(
input
);

classSearch.value='';

});



selectedBox.addEventListener(
'click',
function(e){

if(
e.target.classList.contains(
'remove-class-btn'
)
){

const div=
e.target.closest(
'[data-class-id]'
);

const classId=
div.dataset.classId;

selectedClasses=
selectedClasses.filter(
id=>id!==classId
);

div.remove();

const input=
selectedInputs.querySelector(
`input[data-class-id="${classId}"]`
);

if(input){

input.remove();

}

}

});

</script>

@endsection
