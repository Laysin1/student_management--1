@extends('layouts.student')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Student Settings
            </h1>

            <p class="text-gray-600 mt-1">
                Update your contact information.
            </p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Profile Card -->
            <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">
                <div class="flex flex-col items-center text-center">

                    <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-3xl font-bold mb-4">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    <h2 class="text-xl font-bold text-gray-900">
                        {{ $student->first_name }} {{ $student->last_name }}
                    </h2>

                    <p class="text-gray-500 text-sm mt-1">
                        {{ auth()->user()->email }}
                    </p>

                    <div class="mt-6 w-full space-y-3 text-left">

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500">
                                Student ID
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $student->student_id ?? 'N/A' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500">
                                Class
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $student->class->grade_level ?? '' }}
                                {{ $student->class->name ?? 'Not Assigned' }}
                            </p>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500">
                                Gender
                            </p>

                            <p class="font-semibold text-gray-900">
                                {{ $student->gender ?? 'N/A' }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Settings Form -->
            <div class="lg:col-span-2">

                <form action="{{ route('student.setting.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="bg-white rounded-2xl shadow border border-gray-100 p-6">

                        <h3 class="text-lg font-bold text-gray-900 mb-6">
                            Contact Information
                        </h3>

                        <div class="space-y-5">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name
                                </label>

                                <input
                                    type="text"
                                    value="{{ $student->first_name }} {{ $student->last_name }}"
                                    class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-3 text-gray-500 cursor-not-allowed"
                                    readonly
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    value="{{ auth()->user()->email }}"
                                    class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-3 text-gray-500 cursor-not-allowed"
                                    readonly
                                >
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    name="phone_number"
                                    value="{{ old('phone_number', $student->phone_number ?? '') }}"
                                    placeholder="Enter your phone number"
                                    class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                                >

                                <p class="text-xs text-gray-500 mt-2">
                                    Only your phone number can be updated.
                                </p>
                            </div>
                            <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                placeholder="Leave blank to keep current password"
                                class="w-full rounded-xl border-gray-300 px-4 py-3 focus:border-blue-500 focus:ring-blue-500"
                            >

                            <p class="text-xs text-gray-500 mt-2">
                                Leave empty if you do not want to change your password.
                            </p>
                        </div>

                        </div>

                        <div class="mt-8 flex flex-col sm:flex-row gap-3">

                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-semibold shadow"
                            >
                                Save Changes
                            </button>

                            <a
                                href="{{ route('dashboard.student') }}"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-8 py-3 rounded-xl font-semibold text-center"
                            >
                                Cancel
                            </a>

                        </div>

                    </div>
                </form>

                <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-2xl p-5">
                    <h4 class="font-bold text-yellow-900 mb-2">
                        Important Notice
                    </h4>

                    <p class="text-sm text-yellow-800">
                        If you need to change your name, email, class, or other academic information,
                        please contact the school administrator.
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
