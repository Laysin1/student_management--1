<!-- resources/views/role-selector.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Role</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen bg-green-100 relative">
    <div class="text-center">
        <h1 class="text-3xl font-bold mb-8">Choose your Role to Continue</h1>
        <div class="flex flex-wrap justify-center gap-8 md:gap-16">
            <!-- Admin -->
            <a href="{{ route('login.admin') }}" class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105">
                <img src="{{ asset('images/admin.png') }}" alt="Admin" class="w-24 h-24 mb-4">
                <span class="font-semibold">ADMIN</span>
            </a>

            <!-- Teacher -->
            <a href="{{ route('login.teacher') }}" class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105">
                <img src="{{ asset('images/teacher.jpg') }}" alt="Teacher" class="w-24 h-24 mb-4">
                <span class="font-semibold">TEACHER</span>
            </a>

            <!-- Student -->
            <a href="{{ route('login.student') }}" class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105">
                <img src="{{ asset('images/student.png') }}" alt="Student" class="w-24 h-24 mb-4">
                <span class="font-semibold">STUDENT</span>
            </a>

            <!-- Parent -->
            <a href="{{ route('login.parent') }}" class="flex flex-col items-center bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 hover:scale-105">
                <img src="{{ asset('images/parent.png') }}" alt="Parent" class="w-24 h-24 mb-4">
                <span class="font-semibold">PARENT</span>
            </a>

        </div>
    </div>

    <!-- Optional: Background chalkboard style -->
    <img src="{{ asset('images/background.jpg') }}" alt="Background" class="absolute inset-0 w-full h-full object-cover -z-10 opacity-30">
</body>
</html>
