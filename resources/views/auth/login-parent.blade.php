<!-- resources/views/auth/login-parent.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-900 flex items-center justify-center relative">

    <!-- Fullscreen Background Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('images/admin-bg.jpg') }}" alt="Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/50"></div> <!-- Dark overlay -->
    </div>

    <!-- Glass Card Login Form -->
    <div class="relative bg-white/20 backdrop-blur-md rounded-xl shadow-lg p-10 w-full max-w-sm text-center z-10">
        <!-- User Icon -->
        <div class="w-20 h-20 mx-auto flex items-center justify-center rounded-full bg-white/30 mb-4">
            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
        </div>

        <h1 class="text-2xl font-semibold text-white mb-2">Parent Login</h1>
        <p class="text-white/80 text-sm mb-6">Welcome onboard with us!</p>

        <form method="POST" action="{{ route('login.parent.submit') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <input type="email" name="email" placeholder="Enter your email" required
                    class="w-full py-2 px-4 rounded-lg bg-white/50 text-gray-900 placeholder-gray-700 focus:ring-2 focus:ring-yellow-500 focus:outline-none" />
                @error('email')
                    <p class="mt-1 text-yellow-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <input type="password" name="password" placeholder="Enter your password" required
                    class="w-full py-2 px-4 rounded-lg bg-white/50 text-gray-900 placeholder-gray-700 focus:ring-2 focus:ring-yellow-500 focus:outline-none" />
                @error('password')
                    <p class="mt-1 text-yellow-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold py-2 rounded-lg mt-2">
                Log In as Parent
            </button>
        </form>

        <!-- Not Parent Link -->
        <p class="mt-4 text-sm text-white/80">
            <a href="{{ route('role.selector') }}" class="hover:text-yellow-400">Not parent?</a>
        </p>
    </div>

</body>
</html>
