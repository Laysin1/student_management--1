@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">✏️ Edit User</h2>

    <form action="{{ route('users.update', $id ?? 1) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" value="John Teacher" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" value="john@example.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-control" required>
                <option value="teacher" selected>Teacher</option>
                <option value="student">Student</option>
                <option value="parent">Parent</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
