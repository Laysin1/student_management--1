<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUPP Parent Portal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex">

    <!-- LEFT SIDEBAR -->
    <aside class="w-64 h-screen bg-blue-900 text-white fixed top-0 left-0 flex flex-col">
        <div class="p-6 flex flex-col items-center">
            <img src="{{ asset('images/parent.png') }}" class="w-20 mb-3">
            <h1 class="text-lg font-semibold text-center">Parent</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard.parent') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('dashboard/parent*') ? 'bg-blue-800' : '' }}">
                Home
            </a>
            <a href="{{ route('parent.classes') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('parent/classes*') ? 'bg-blue-800' : '' }}">
                Children
            </a>
            <a href="{{ route('parent.schedule') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('parent/schedule*') ? 'bg-blue-800' : '' }}">
                Schedule
            </a>
        </nav>

        <div class="p-4 border-t border-blue-700">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 rounded-lg hover:bg-blue-800 text-red-300">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- PAGE CONTENT -->
    <main class="ml-64 w-full p-8">
        @yield('content')
    </main>

</div>

</body>
</html>
