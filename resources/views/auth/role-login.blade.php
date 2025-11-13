<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ strtoupper($role) }} Login</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-10 rounded-2xl shadow-lg w-full max-w-md text-center">
        <img src="{{ asset('images/' . $role . '.png') }}" alt="{{ $role }}" class="w-28 h-28 mx-auto mb-6">
        <h1 class="text-2xl font-bold mb-6">{{ strtoupper($role) }} LOGIN</h1>

        <form action="{{ route('login.submit', ['role' => $role]) }}" method="POST" class="space-y-4">
            @csrf
            <input type="email" name="email" placeholder="Email" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="password" name="password" placeholder="Password" class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400">
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">Login</button>
        </form>

        <a href="{{ route('role.selector') }}" class="mt-4 inline-block text-sm text-blue-600 hover:underline">Back to Role Selector</a>
    </div>
</body>
</html>
