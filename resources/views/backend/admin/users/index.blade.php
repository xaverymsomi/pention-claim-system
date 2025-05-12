@extends('layouts.master')
@section('content')

    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h4 font-weight-bold text-dark">User Management</h1>
            <p class="text-muted">Manage all system users here.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Add User</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-3">
        <form method="GET" class="d-flex align-items-center gap-2">
            <label for="role" class="form-label mb-0">Filter by Role:</label>
            <select name="role" id="role" class="form-control">
                <option value="">All</option>
                <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                <option value="member" {{ request('role') === 'member' ? 'selected' : '' }}>Member</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="btn btn-secondary">Apply</button>
        </form>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users->whereNotIn('user_type_id', [1]) as $index => $user)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td class="text-capitalize">{{ $user->getRoleName() }}</td>
                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-info text-white">Edit</a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $users->links() }}
    </div>

@endsection
