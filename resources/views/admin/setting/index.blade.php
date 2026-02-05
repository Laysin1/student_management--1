@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8 space-y-8">

    <!-- Page Title -->
    <h2 class="text-3xl font-bold text-gray-800 border-b pb-3">Profile</h2>

    <!-- Profile Display -->
    <div id="profile-view" class="space-y-6">

        <div class="flex justify-center mb-4">
            <img src="https://web-new.rupp.edu.kh/wp-content/uploads/2025/02/logo-rupp-1-1024x1024.png" alt="Profile Picture" class="w-28 h-28 rounded-full border-2 border-gray-300 shadow-sm">
        </div>

        <div class="space-y-4">
            <div>
                <label class="block text-gray-700 font-semibold mb-1">First Name <span class="text-red-500">*</span></label>
                <input type="text" value="{{ Auth::user()->first_name ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Last Name <span class="text-red-500">*</span></label>
                <input type="text" value="{{ Auth::user()->last_name ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" value="{{ Auth::user()->email }}" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Contact Number</label>
                <input type="text" value="{{ Auth::user()->contact_number ?? 'N/A' }}" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" value="********" readonly class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 cursor-not-allowed">
            </div>
        </div>

        <div class="flex flex-wrap mt-4 gap-4">
    <button id="edit-btn" class="flex items-center space-x-2 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded shadow mr-12">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2M12 11v2m6 6H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v7a2 2 0 01-2 2z" />
        </svg>
        <span>Edit</span>
    </button>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="flex items-center space-x-2 px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded shadow">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1m0-5V7m0 0H5a2 2 0 00-2 2v6a2 2 0 002 2h8z" />
            </svg>
            <span>Logout</span>
        </button>
    </form>
</div>

    </div>

    <!-- Profile Edit Form -->
    <div id="profile-edit" class="hidden space-y-6">
        <form method="POST" action="{{ route('setting.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">First Name <span class="text-red-500">*</span></label>
                    <input type="text" name="first_name" value="{{ Auth::user()->first_name }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Last Name <span class="text-red-500">*</span></label>
                    <input type="text" name="last_name" value="{{ Auth::user()->last_name }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ Auth::user()->email }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Contact Number</label>
                <input type="text" name="contact_number" value="{{ Auth::user()->contact_number }}" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div>
                <label class="block text-gray-700 font-semibold mb-1">Password</label>
                <input type="password" name="password" placeholder="Enter new password if you want to change" class="w-full border border-gray-300 rounded px-3 py-2 focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex space-x-10 mt-4 gap-4">
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 font-semibold">
                    Save
                </button>
                <button type="button" id="cancel-btn" class="px-4 py-2 bg-gray-500 text-gray-800 rounded hover:bg-gray-500 font-semibold">
                    Cancel
                </button>
            </div>


        </form>
    </div>

</div>

<script>
    const editBtn = document.getElementById('edit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const profileView = document.getElementById('profile-view');
    const profileEdit = document.getElementById('profile-edit');

    editBtn.addEventListener('click', () => {
        profileView.classList.add('hidden');
        profileEdit.classList.remove('hidden');
    });

    cancelBtn.addEventListener('click', () => {
        profileEdit.classList.add('hidden');
        profileView.classList.remove('hidden');
    });
</script>
@endsection
