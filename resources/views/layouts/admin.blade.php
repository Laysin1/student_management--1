<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RUPP Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100">

<div class="flex">

    <!-- LEFT SIDEBAR -->
    <aside class="w-64 h-screen bg-blue-900 text-white fixed top-0 left-0 flex flex-col">
        <div class="p-6 flex flex-col items-center">
            <img src="https://web-new.rupp.edu.kh/wp-content/uploads/2025/02/logo-rupp-1-1024x1024.png" class="w-20 mb-3">
            <h1 class="text-lg font-semibold text-center">RUPP Admin</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('dashboard.admin') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('dashboard/admin*') ? 'bg-blue-800' : '' }}">
                Dashboard
            </a>
            <a href="{{ route('teachers.index') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('admin/teachers*') ? 'bg-blue-800' : '' }}">
                Teachers
            </a>
            <a href="{{ route('classes.index') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('admin/class*') ? 'bg-blue-800' : '' }}">
                Class
            </a>
            <a href="{{ route('schedules.index') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('admin/schedule*') ? 'bg-blue-800' : '' }}">
                Schedule
            </a>
            <a href="{{ route('setting.index') }}"
               class="block px-4 py-3 rounded-lg hover:bg-blue-800 {{ request()->is('admin/setting*') ? 'bg-blue-800' : '' }}">
                Setting
            </a>
        </nav>
    </aside>

    <!-- PAGE CONTENT -->
    <main class="ml-64 w-full p-8">
        @yield('content')
    </main>

</div>

</body>
</html>
