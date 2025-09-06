<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="{{ url('logo/GemalaRiau.jpg') }}" type="image/png">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen font-sans">

    <div class="w-full max-w-sm bg-white rounded-xl shadow-lg p-8">
        
        <!-- Logo -->
        <div class="flex justify-center mb-8">
            <img src="{{ url('logo/gr1.png') }}" alt="Logo Perusahaan" class="h-16">
        </div>

        <!-- Menampilkan pesan error jika login gagal -->
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4 text-sm" role="alert">
                <span class="block sm:inline">{{ $errors->first() }}</span>
            </div>
        @endif
        
        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <!-- Input Email -->
            <div class="mb-4">
                <label for="email" class="block text-gray-600 text-sm font-medium mb-2 sr-only">Email</label>
                <input id="email" type="email" name="email" required autofocus 
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Email">
            </div>

            <!-- Input Password -->
            <div class="mb-6">
                <label for="password" class="block text-gray-600 text-sm font-medium mb-2 sr-only">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Password">
            </div>

            <!-- Tombol Login -->
            <div class="flex items-center justify-center">
                <button type="submit"
                        class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:shadow-outline">
                    Login
                </button>
            </div>
        </form>
    </div>

</body>
</html>
