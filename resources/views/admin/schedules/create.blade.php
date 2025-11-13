@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Add New Schedule</h1>

    @if($errors->any())
        <div class="bg-red-200 text-red-800 p-3 mb-4 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="font-semibold">Class:</label>
            <select name="class_id" class="border rounded p-2 w-full">
                @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Teacher:</label>
            <select name="teacher_id" class="border rounded p-2 w-full">
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Day:</label>
            <select name="day" class="border rounded p-2 w-full">
                @foreach($days as $day)
                    <option value="{{ $day }}">{{ $day }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Time Slot:</label>
            <select name="time_slot" class="border rounded p-2 w-full">
                @foreach($timeSlots as $slot)
                    <option value="{{ $slot }}">{{ $slot }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="font-semibold">Subject:</label>
            <select name="subject" class="border rounded p-2 w-full">
                @foreach($subjects as $subject)
                    <option value="{{ $subject }}">{{ $subject }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Save Schedule</button>
    </form>
</div>
@endsection
