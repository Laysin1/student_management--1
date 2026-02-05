<!-- filepath: resources/views/layouts/teacher.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow h-screen flex flex-col justify-between fixed left-0 top-0 z-20">
        <div>
            <div class="px-6 py-6 border-b">
                <a href="{{ route('dashboard.teacher') }}" class="text-2xl font-bold text-blue-700">Teacher Panel</a>
            </div>
            <nav class="flex flex-col gap-1 mt-6 px-4">
                <a href="{{ route('dashboard.teacher') }}" class="px-4 py-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-medium {{ request()->routeIs('dashboard.teacher') ? 'bg-blue-100 text-blue-700' : '' }}">
                    Home
                </a>
                <a href="{{ route('teacher.classes.index') }}" class="px-4 py-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-medium {{ request()->routeIs('classes.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                    Classes
                </a>
                <a href="{{ route('teacher.schedule.index') }}" class="px-4 py-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-medium {{ request()->routeIs('schedule.*') ? 'bg-blue-100 text-blue-700' : '' }}">
                    Schedule
                </a>
                <a href="{{ route('teacher.setting.index') }}" class="px-4 py-2 rounded text-gray-700 hover:bg-blue-50 hover:text-blue-700 font-medium {{ request()->routeIs('setting.index') ? 'bg-blue-100 text-blue-700' : '' }}">
                    Setting
                </a>
            </nav>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="px-6 py-4 border-t">
            @csrf
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded font-semibold">Logout</button>
        </form>
    </aside>

    <!-- Main content, with left margin for sidebar -->
    <main class="flex-1 ml-64 px-6 py-8">
        @yield('content')
    </main>
</body>
</html>
