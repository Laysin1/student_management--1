@extends('layouts.teacher')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="font-bold text-3xl text-gray-900 mb-2">Settings</h2>
            <p class="text-gray-600">Manage your profile and account settings</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('teacher.setting.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Personal Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500"
                               required>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Email Address</label>
                        <input type="email" value="{{ $user->email }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full bg-gray-100 text-gray-500 cursor-not-allowed"
                               readonly>
                        <small class="text-gray-500 mt-1 block">
                            Email cannot be changed by teacher.
                        </small>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Contact Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Phone Number</label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number', $teacher->phone_number ?? '') }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-green-500">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Gender</label>
                        <select name="gender" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-green-500">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $teacher->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $teacher->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $teacher->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Professional Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Subject</p>
                        <p class="font-semibold text-gray-900">
                            {{ $teacherSubject->name ?? 'Not assigned' }}
                        </p>

                    </div>

                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Classes Assigned</p>
                        <p class="font-semibold text-gray-900">
                            {{ $teacher->classes->count() ?? 0 }} class(es)
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Account Security
                </h3>

                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">Change Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password"
                           class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-indigo-500">
                    <small class="text-gray-500 mt-1 block">
                        Leave empty to keep your current password.
                    </small>
                </div>
            </div>

            {{-- <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">Account Information</h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-900">
                    <div>
                        <span class="text-blue-600">Teacher ID:</span> #{{ $teacher->id }}
                    </div>

                    <div>
                        <span class="text-blue-600">Member Since:</span>
                        {{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div> --}}

            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-semibold">
                    Save Settings
                </button>

                <a href="{{ route('dashboard.teacher') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-8 py-2 rounded-lg font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
