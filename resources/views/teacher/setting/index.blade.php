@extends('layouts.teacher')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="font-bold text-3xl text-gray-900 mb-2">Settings</h2>
            <p class="text-gray-600">Manage your profile and account settings</p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
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

            <!-- Profile Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    Personal Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    </div>
                </div>
            </div>

            <!-- Contact & Personal Details Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.834l.74 4.435a1 1 0 01-.54 1.06l-1.77.886a16.112 16.112 0 006.837 6.837l.886-1.77a1 1 0 011.06-.54l4.435.74a1 1 0 01.834.986V17a2 2 0 01-2 2h-2.183C7.168 19 2 13.832 2 7.183V5a2 2 0 012-2z"/></svg>
                    Contact Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Phone Number</label>
                        <input type="tel" name="phone_number" value="{{ old('phone_number', $teacher->phone_number ?? '') }}"
                               placeholder="+1 (555) 123-4567"
                               class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Gender</label>
                        <select name="gender" class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $teacher->gender ?? '') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $teacher->gender ?? '') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Other" {{ old('gender', $teacher->gender ?? '') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/></svg>
                    Professional Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Subject</p>
                        <p class="font-semibold text-gray-900">{{ optional($teacher->subject)->name ?? 'Not assigned' }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded border border-gray-200">
                        <p class="text-sm text-gray-600 mb-1">Classes Assigned</p>
                        <p class="font-semibold text-gray-900">{{ $teacher->classes->count() ?? 0 }} class(es)</p>
                    </div>
                </div>
            </div>

            <!-- Account Status Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                    Account Security
                </h3>
                <div>
                    <label class="font-semibold text-gray-800 mb-2 block">Change Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current password"
                           class="border border-gray-300 rounded px-3 py-2 w-full focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <small class="text-gray-500 mt-1 block">Leave empty to keep your current password. Minimum 8 characters required.</small>
                </div>
            </div>

            <!-- Account Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-900 mb-2">Account Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-900">
                    <div>
                        <span class="text-blue-600">Teacher ID:</span> #{{ $teacher->id }}
                    </div>
                    <div>
                        <span class="text-blue-600">Member Since:</span> {{ $user->created_at->format('M d, Y') }}
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
                    Save Settings
                </button>
                <a href="{{ route('dashboard.teacher') }}" class="bg-gray-400 hover:bg-gray-500 text-white px-8 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
