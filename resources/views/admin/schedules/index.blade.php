@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Schedule</h1>

    <!-- Class filter -->
    <form method="GET" action="{{ route('schedules.index') }}" class="mb-4">
        <label for="class_id" class="mr-2 font-semibold">Select Class:</label>
        <select name="class_id" id="class_id" class="border rounded p-2">
            @foreach($classes as $class)
                <option value="{{ $class->id }}" {{ $selectedClass == $class->id ? 'selected' : '' }}>
                    {{ $class->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
    </form>

    <a href="{{ route('schedules.create') }}" class="bg-purple-500 text-white px-4 py-2 rounded mb-4 inline-block">
        Add New Schedule
    </a>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-200 text-red-800 p-3 mb-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">Time Slot</th>
                @foreach($days as $day)
                    <th class="border px-4 py-2">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($timeSlots as $slot)
                <tr>
                    <td class="border px-4 py-2 font-semibold">{{ $slot }}</td>
                    @foreach($days as $day)
                        <td class="border px-4 py-2">
                            {{ $scheduleData[$slot][$day] ?? '' }}
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
