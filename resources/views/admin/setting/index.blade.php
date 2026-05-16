@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-5xl">

    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Admin Settings</h1>
        <p class="text-gray-600 mt-1">View and update your admin profile information</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-5">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-5">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center gap-5">
            <div class="w-24 h-24 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-3xl border">
                {{ strtoupper(substr(Auth::user()->first_name ?? Auth::user()->name ?? 'A', 0, 1)) }}
            </div>

            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ Auth::user()->first_name ?? '' }} {{ Auth::user()->last_name ?? '' }}
                </h2>

                <p class="text-gray-600 mt-1">
                    {{ Auth::user()->email }}
                </p>

                <span class="inline-flex mt-2 px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                    Admin Account
                </span>
            </div>
        </div>

        <div class="p-6">

            <div id="profile-view">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Profile Information</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <div class="text-sm text-gray-500">First Name</div>
                        <div class="text-gray-800 font-medium">{{ Auth::user()->first_name ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Last Name</div>
                        <div class="text-gray-800 font-medium">{{ Auth::user()->last_name ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Email</div>
                        <div class="text-gray-800 font-medium">{{ Auth::user()->email ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Contact Number</div>
                        <div class="text-gray-800 font-medium">{{ Auth::user()->contact_number ?? '—' }}</div>
                    </div>

                    <div>
                        <div class="text-sm text-gray-500">Password</div>
                        <div class="text-gray-800 font-medium">********</div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-6">
                    <button type="button"
                            id="edit-btn"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold">
                        Edit Profile
                    </button>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-5 py-2 rounded-lg font-semibold">
                            Logout
                        </button>
                    </form>
                </div>
            </div>

            <div id="profile-edit" class="hidden">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Profile</h3>

                <form method="POST" action="{{ route('setting.update') }}" class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold text-gray-800 mb-2 block">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="first_name"
                                   value="{{ old('first_name', Auth::user()->first_name) }}"
                                   class="border border-gray-300 rounded px-3 py-2 w-full" required>
                        </div>

                        <div>
                            <label class="font-semibold text-gray-800 mb-2 block">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   name="last_name"
                                   value="{{ old('last_name', Auth::user()->last_name) }}"
                                   class="border border-gray-300 rounded px-3 py-2 w-full" required>
                        </div>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email"
                               name="email"
                               value="{{ old('email', Auth::user()->email) }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full" required>
                    </div>

                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">Contact Number</label>
                        <input type="text"
                               name="contact_number"
                               value="{{ old('contact_number', Auth::user()->contact_number) }}"
                               class="border border-gray-300 rounded px-3 py-2 w-full">
                    </div>

                    <div>
                        <label class="font-semibold text-gray-800 mb-2 block">New Password</label>
                        <input type="password"
                               name="password"
                               placeholder="Leave blank to keep current password"
                               class="border border-gray-300 rounded px-3 py-2 w-full">
                        <small class="text-gray-500">Only fill this if you want to change your password.</small>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded font-semibold">
                            Save Changes
                        </button>

                        <button type="button"
                                id="cancel-btn"
                                class="bg-gray-400 hover:bg-gray-500 text-white px-6 py-2 rounded font-semibold">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
    const editBtn = document.getElementById('edit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const profileView = document.getElementById('profile-view');
    const profileEdit = document.getElementById('profile-edit');

    editBtn?.addEventListener('click', () => {
        profileView.classList.add('hidden');
        profileEdit.classList.remove('hidden');
    });

    cancelBtn?.addEventListener('click', () => {
        profileEdit.classList.add('hidden');
        profileView.classList.remove('hidden');
    });
</script>
@endsection
