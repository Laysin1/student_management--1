@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6 max-w-7xl">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Parents Management
            </h1>

            <p class="text-gray-600 mt-1">
                Manage all parent accounts and linked students
            </p>
        </div>

        <a href="{{ route('parents.create') }}"
           class="inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg shadow-sm">
            + Add Parent
        </a>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-5">
            {{ session('success') }}
        </div>
    @endif

    <!-- Search -->
    <form method="GET"
          action="{{ route('parents.index') }}"
          class="bg-white rounded-xl shadow border border-gray-100 p-4 mb-5">

        <div class="flex flex-col md:flex-row gap-3">

            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search parent by name, email, phone, or occupation..."
                   class="border border-gray-300 rounded-lg px-4 py-2 w-full">

            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg font-semibold">
                Search
            </button>

            @if(request('search'))
                <a href="{{ route('parents.index') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-5 py-2 rounded-lg font-semibold text-center">
                    Clear
                </a>
            @endif

        </div>
    </form>

    <!-- Table Card -->
    <div class="bg-white rounded-xl shadow border border-gray-100 overflow-hidden">

        <!-- Table -->
        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>

                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Parent
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Email
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Phone
                        </th>

                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Occupation
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Students
                        </th>

                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($parents as $parent)

                        <tr class="hover:bg-gray-50 transition">

                            <!-- Parent -->
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold">
                                        {{ strtoupper(substr($parent->first_name, 0, 1)) }}
                                    </div>

                                    <div>
                                        <div class="font-semibold text-gray-900">
                                            {{ $parent->first_name }} {{ $parent->last_name }}
                                        </div>

                                        <div class="text-sm text-gray-500">
                                            Parent Account
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <!-- Email -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ optional($parent->user)->email ?? '—' }}
                            </td>

                            <!-- Phone -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $parent->phone ?? '—' }}
                            </td>

                            <!-- Occupation -->
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $parent->occupation ?? '—' }}
                            </td>

                            <!-- Students -->
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center justify-center min-w-[32px] h-8 px-2 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                                    {{ $parent->students->count() }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-3">

                                    <a href="{{ route('parents.edit', $parent->id) }}"
                                       class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('parents.destroy', $parent->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete this parent?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-semibold text-sm">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center">

                                <div class="text-gray-400 text-lg mb-1">
                                    No parents found
                                </div>

                                <p class="text-gray-500 text-sm">
                                    Start by creating a new parent account.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $parents->links() }}
    </div>

</div>
@endsection
