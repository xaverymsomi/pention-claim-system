@extends('layouts.master')
@section('content')
    <div class="container mt-4">
        <div class="card mx-auto shadow-sm" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="h4 font-weight-bold mb-4 text-dark">Add New User</h1>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="user_type_id" class="form-control" required>
                            <option value="2" {{ old('role') === 'member' ? 'selected' : '' }}>Member</option>
                            <option value="3" {{ old('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="1" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Create User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

