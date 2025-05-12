@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 font-weight-bold text-dark mb-4">Assign Claim to Staff</h1>

                <form method="POST" action="{{ route('admin.claims.assignStaff', $claim) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Select Staff</label>
                        <select name="assigned_to" required class="form-control">
                            <option value="">-- Choose Staff --</option>
                            @foreach ($staff as $user)
                                <option value="{{ $user->id }}" {{ $claim->assigned_to == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Assign</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
