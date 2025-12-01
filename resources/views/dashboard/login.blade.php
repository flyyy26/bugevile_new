<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
     <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white shadow-md rounded-lg p-8 w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Login</h2>

        @if (session('error'))
            <p class="text-red-500 mb-4 text-center">{{ session('error') }}</p>
        @endif

        <form action="/dashboard/login" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="username" class="block text-gray-700 font-medium mb-1">Username</label>
                <input type="text" name="username" id="username" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

            <div>
                <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                <input type="password" name="password" id="password" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors">
                Login
            </button>
        </form>

        <p class="mt-4 text-center text-gray-500 text-sm">© 2025 Bugevile.inc</p>
    </div>

</body>
</html>
