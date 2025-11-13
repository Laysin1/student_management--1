@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">Admin Dashboard</h1>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
        <a href="{{ route('admins.index') }}" class="bg-blue-500 text-white p-6 rounded-xl shadow hover:bg-blue-600">
            Manage Admins
        </a>
        <a href="{{ route('users.index') }}" class="bg-green-500 text-white p-6 rounded-xl shadow hover:bg-green-600">
            Manage Users
        </a>
        <a href="{{ route('announcements.index') }}" class="bg-yellow-500 text-white p-6 rounded-xl shadow hover:bg-yellow-600">
            Announcements
        </a>
        <a href="{{ route('schedules.index') }}" class="bg-purple-500 text-white p-6 rounded-xl shadow hover:bg-purple-600">
            Manage Schedules
        </a>
        <a href="{{ route('subjects.index') }}" class="bg-pink-500 text-white p-6 rounded-xl shadow hover:bg-pink-600">
            Manage Subjects
        </a>
    </div>
</div>
@endsection
