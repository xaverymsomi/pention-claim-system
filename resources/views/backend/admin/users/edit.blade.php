@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 600px;">
        <h1 class="h4 font-weight-bold mb-4 text-dark">Edit User: {{ $user->name }}</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="user_type_id" class="form-select">
                            <option value="2" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="3" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">New Password (optional)</label>
                        <input type="password" name="password"
                               class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation"
                               class="form-control">
                    </div>

                    <div class="d-flex justify-content-start gap-3">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-link">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

