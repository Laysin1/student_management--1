@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Add New Admin</h1>

    <form action="{{ route('admins.store') }}" method="POST" class="max-w-md">
        @csrf
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Save Admin</button>
    </form>
</div>
@endsection
