@extends('layouts.admin')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Create Parent Account</h1>

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
            <form action="{{ route('parents.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Students (Optional)</label>
                    <select name="student_ids[]" multiple class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Hold Ctrl to select multiple students</p>
                </div>

                <div class="mt-8 flex gap-4">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded">
                        Create Parent
                    </button>
                    <a href="{{ route('parents.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
