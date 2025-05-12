<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSKMS User Register and Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('/test/css/sweetalert2.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Include jQuery before Toastr -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-vtXRMe3mGCbOeY7l30aIg8H9p3GdeSe4IFlP6G8JMa7o7lXvnz3GFKzPxzJdPfGK" crossorigin="anonymous">
    </script>
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })
    </script>

<style>
    .txt-center {
        text-align: center;
    }
    /* body {
        background-color: #df7c11;
    } */
    body {
            /* background-color: #df7c11; */
            background-image: url('/test/images/korosho.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            
        }

</style>

    <meta name="csrf-token" content="{{ csrf_token() }}"> 
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('login') ? 'active' : '' }}"
                                href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('register') ? 'active' : '' }}"
                                href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->surname }}
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">Logout</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
                {{-- <div class="d-flex flex-column align-items-end">
                    <h5 class="mb-2">Search Status</h5>
                    <form id="search-form" class="d-flex">
                        <div class="input-group">
                            <input type="text" class="form-control" id="approval_number" name="approval_number"
                                placeholder="Content number">
                            <input type="text" class="form-control" id="phone_number" name="phone_number"
                                placeholder="Phone number">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div> --}}
            </div>
        </div>
    </nav>

    <div class="container mt-4 content-container">
        @yield('content')
    </div>
    {{-- <div class="container mt-4">
        @yield('content')
    </div> --}}

    <script>
        $(document).ready(function() {
            $('#search-form').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                var formData = $(this).serialize(); // Serialize form data

                $.ajax({
                    url: "{{ route('search') }}",
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token from meta tag
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Content Data Found Successful',
                                text: data.success,
                            });
                            setTimeout(function() {
                                window.location.reload(); 
                            }, 4000);
                        }
                        if (data.errors) {
                            Swal.fire({
                                icon: 'info',
                                title: 'Content Data errors',
                                text: data.errors,
                            });
                            setTimeout(function() {
                                window.location.reload(); 
                            }, 4000);
                        } 
                        
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error); 
                        Swal.fire({
                            icon: 'error',
                            title: 'An error occurred',
                            text: 'Please try again later.',
                        });
                    }
                });
            });
        });

    </script>

</body>

</html>
