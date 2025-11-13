@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Subjects</h1>

    <a href="{{ route('subjects.create') }}" class="bg-pink-500 text-white px-4 py-2 rounded mb-4 inline-block">
        Add New Subject
    </a>

    @if(session('success'))
        <div class="bg-green-200 text-green-800 p-3 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Code</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subjects ?? [] as $subject)
                <tr>
                    <td class="border px-4 py-2">{{ $subject->id }}</td>
                    <td class="border px-4 py-2">{{ $subject->name }}</td>
                    <td class="border px-4 py-2">{{ $subject->code }}</td>
                    <td class="border px-4 py-2">
                        <a href="{{ route('subjects.edit', $subject->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Edit</a>
                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
