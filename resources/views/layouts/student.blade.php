<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Student Dashboard') - School Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen flex">
    <!-- Fixed Sidebar Navigation -->
    <aside class="w-64 bg-white shadow-lg h-screen flex flex-col justify-between fixed left-0 top-0 z-20">
        <div>
            <div class="px-6 py-6 border-b border-gray-200">
                <a href="{{ route('dashboard.student') }}" class="text-2xl font-bold text-blue-600">RUPP</a>
            </div>
            <nav class="flex flex-col gap-1 mt-6 px-4">
                <a href="{{ route('dashboard.student') }}" class="px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition {{ request()->routeIs('dashboard.student') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </div>
                </a>

                <a href="{{ route('student.scores') }}" class="px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition {{ request()->routeIs('student.scores') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        Grades
                    </div>
                </a>

                <a href="{{ route('student.attendance') }}" class="px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition {{ request()->routeIs('student.attendance') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        Attendance
                    </div>
                </a>

                {{-- <a href="{{ route('student.classes.index') }}" class="px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition {{ request()->routeIs('student.classes.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.5 1.5H3a1.5 1.5 0 00-1.5 1.5v12a1.5 1.5 0 001.5 1.5h10a1.5 1.5 0 001.5-1.5V11m-10 6l2.757-5.351a.6.6 0 01.16-.158l2.6-2.589a.6.6 0 01.848 0l2.034 2.034a.6.6 0 01.848-.848L9.856 7.363M5 10a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        </svg>
                        Classes
                    </div>
                </a> --}}

                <a href="{{ route('student.setting.index') }}" class="px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 font-medium transition {{ request()->routeIs('student.setting.index') ? 'bg-blue-100 text-blue-600' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                        Settings
                    </div>
                </a>
            </nav>
        </div>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}" class="px-6 py-4 border-t border-gray-200">
            @csrf
            <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition">
                Logout
            </button>
        </form>
    </aside>

    <!-- Main Content with left margin for sidebar -->
    <main class="flex-1 ml-64 px-6 py-8">
        @yield('content')
    </main>
</body>
</html>
