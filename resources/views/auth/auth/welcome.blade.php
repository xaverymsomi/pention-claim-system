<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NSSF Pensions Claim System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/test/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Include jQuery, Toastr, and SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                    <a href="{{ route('register') }}" class="text-green-700 hover:underline">Register</a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 py-12 grid md:grid-cols-2 gap-10">
            <!-- About Section -->
            <div class="bg-white bg-opacity-90 p-8 rounded-xl shadow-md">
                <h2 class="text-3xl font-bold text-green-800 mb-4">Welcome to the NSSF Pension Claim System</h2>
                <p class="text-gray-700 mb-4">
                    A modern platform to submit, track, and manage your pension claims — securely, efficiently, and
                    digitally.
                </p>
                <h3 class="text-xl font-semibold text-green-700 mt-6 mb-2">What This System Does</h3>
                <ul class="list-disc list-inside text-gray-700 mb-4">
                    <li>User registration and authentication</li>
                    <li>Members profile management</li>
                    <li>Pension claims submission, tract status and receive updates</li>
                    <li>Notification and alerts</li>
                    <li>Data privacy and security</li>
                    <li>Mobile and web platform compatibility</li>
                </ul>
                <h3 class="text-xl font-semibold text-green-700 mt-6 mb-2">For the Nation</h3>
                <ul class="list-disc list-inside text-gray-700 mb-6">
                    <li>Strengthens digital governance</li>
                    <li>Promotes service delivery and accessibility</li>
                    <li>Supports national financial and compliance goals</li>
                    <li>Reduces paperwork and manual delays</li>
                </ul>
                <div class="mt-8 text-center">
                    <a href="{{ route('register') }}"
                        class="inline-block px-6 py-2 text-white bg-green-700 hover:bg-green-800 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-green-600">
                        Register Now
                    </a>
                </div>
            </div>

            <!-- Login Section -->
            <div class="flex items-center justify-center">
                <div class="bg-white bg-opacity-90 p-8 rounded-xl shadow-md w-full max-w-md">
                    <h2 class="text-2xl font-bold text-green-800 mb-6 text-center">Login to Your Account</h2>

                    <form id="loginForm">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input id="email" type="email" name="email" required
                                class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" />
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-600 focus:border-transparent" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center mb-4">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="remember_me" class="ml-2 text-sm text-gray-600">
                                Remember me
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-center justify-between">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-green-800"
                                    href="{{ route('password.request') }}">
                                    Forgot your password?
                                </a>
                            @endif

                            <button type="submit"
                                class="ml-3 px-5 py-2 text-white bg-green-700 hover:bg-green-800 rounded-lg shadow transition focus:outline-none focus:ring-2 focus:ring-green-600">
                                Log in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                $('.text-red-600').remove();

                $.ajax({
                    url: "{{ route('authenticate') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        email: $('#email').val(),
                        password: $('#password').val(),
                        remember: $('#remember_me').is(':checked') ? 1 : 0
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.success);
                        }

                        if (response.redirect) {
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1500);
                        }

                        if (response.errors) {
                            toastr.error(response.errors);
                        }
                    },
                    error: function(xhr) {
                        let res = xhr.responseJSON;

                        if (res?.errors) {
                            // Handle Laravel validation error format (field => [messages])
                            if (typeof res.errors === 'object') {
                                Object.values(res.errors).forEach(function(messages) {
                                    toastr.error(messages[0]);
                                });
                            }
                            // Handle custom error as string
                            else if (typeof res.errors === 'string') {
                                toastr.error(res.errors);
                            }
                        } else {
                            toastr.error('An unexpected error occurred.');
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>
