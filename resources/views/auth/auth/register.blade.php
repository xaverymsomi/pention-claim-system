<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NSSF Register</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            /* background-image: url('{{ asset('/image/nssf1.png') }}'); */
            background-image: url('{{ asset('/image/nssf.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            min-height: 100vh;
        }

        /* body {
            /* background-color: #df7c11; */
            /* background-size: cover; */
            /* background-position: center; */
            /* background-repeat: no-repeat; */
            
        /* } */ */
        .overlay {
            background-color: rgba(255, 255, 255, 0.85);
            min-height: 100vh;
        }
    </style>
</head>
<body>
<div class="overlay">
    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-semibold text-green-700">NSSF Claim Portal</h1>
            <div class="space-x-4">
                <a href="{{ url('/') }}" class="text-green-700 hover:underline">Home</a>
                <a href="{{ url('/') }}" class="text-green-700 hover:underline">Login</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex items-center justify-center py-12 px-4">
        <div class="bg-white bg-opacity-90 p-8 rounded-xl shadow-md w-full max-w-xl">
            <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Register Your Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input id="name" name="name" type="text" required autofocus autocomplete="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" required autocomplete="username"
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                           class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between">
                    <a href="{{ url('/') }}" class="text-sm text-gray-600 hover:text-green-800 underline">
                        Already registered?
                    </a>
                    <button type="submit"
                            class="ml-3 px-5 py-2 text-white bg-green-700 hover:bg-green-800 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-green-600">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>
