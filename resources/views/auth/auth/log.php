@extends('auth.auth.layouts')

@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header txt-center">Login Form</div>
            <div class="card-body">
                <form id="login-form">
                    @csrf
                    <div class="mb-3 row">
                        <label for="email" class="col-md-4 col-form-label text-md-end text-start">Email Address</label>
                        <div class="col-md-6">
                          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                            <span class="text-danger" id="email-error"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="password" class="col-md-4 col-form-label text-md-end text-start">Password</label>
                        <div class="col-md-6">
                          <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            <span class="text-danger" id="password-error"></span>
                        </div>
                    </div>
                    <div class="mb-3 row">
                            <input type="submit" class="col-md-3 offset-md-5 btn btn-primary" value="Login">
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>

<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#login-form').on('submit', function(event) {
        event.preventDefault(); 

        var formData = $(this).serialize(); 
        $('#email-error').text('');
        $('#password-error').text('');

        $.ajax({
            url: "{{ route('authenticate') }}", // Update with your route for login authentication
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Include CSRF token from meta tag
            },
            dataType: "JSON",
            success: function(data) {
                if (data.success) {
                    var message = data.success;
                    Toast.fire({
                        icon: 'success',
                        title: message,
                    });
                    setTimeout(function() {
                        window.location.href = data.redirect; // Redirect on successful login

                    }, 4000);
                } 
                if (data.errors) {
                    Swal.fire({
                        icon: 'info',
                        title: 'Login errors',
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

@endsection
