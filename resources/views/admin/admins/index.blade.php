@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Manage Admins</h1>

    <a href="{{ route('admins.create') }}" class="bg-green-500 text-white px-4 py-2 rounded shadow hover:bg-green-600 mb-4 inline-block">
        Add New Admin
    </a>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th class="py-2 px-4 border">ID</th>
                <th class="py-2 px-4 border">Name</th>
                <th class="py-2 px-4 border">Email</th>
                <th class="py-2 px-4 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $admin)
            <tr>
                <td class="py-2 px-4 border">{{ $admin->id }}</td>
                <td class="py-2 px-4 border">{{ $admin->name }}</td>
                <td class="py-2 px-4 border">{{ $admin->email }}</td>
                <td class="py-2 px-4 border flex gap-2">
                    <a href="{{ route('admins.edit', $admin->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">Edit</a>
                    <form action="{{ route('admins.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
