@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6">Edit Admin</h1>

    <form action="{{ route('admins.update', $admin->id) }}" method="POST" class="max-w-md">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" value="{{ $admin->name }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Email</label>
            <input type="email" name="email" value="{{ $admin->email }}" class="w-full border rounded px-3 py-2" required>
        </div>
        <div class="mb-4">
            <label class="block mb-1 font-semibold">Password <small>(leave blank to keep current)</small></label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2">
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Update Admin</button>
    </form>
</div>
@endsection
