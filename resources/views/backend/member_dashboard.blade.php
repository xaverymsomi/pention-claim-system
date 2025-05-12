@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="mb-4">
            <h1 class="h4 fw-bold text-dark">Welcome, {{ Auth::user()->name }} 👋</h1>
            <p class="text-muted">Here's a summary of your pension claims.</p>
        </div>

        <!-- Summary Cards -->
        <div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Claims</p>
                        <h4 class="fw-bold text-primary">{{ $total }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Approved</p>
                        <h4 class="fw-bold text-success">{{ $approved }}</h4>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pending</p>
                        <h4 class="fw-bold text-warning">{{ $pending }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Claims -->
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-dark">Recent Claims</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                        <tr>
                            <th>Claim Ref</th>
                            <th>Status</th>
                            <th>Submitted</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($claims as $claim)
                            <tr>
                                <td>{{ $claim->reference }}</td>
                                <td class="text-capitalize
                                    @if($claim->status->name === 'approved') text-success
                                    @elseif($claim->status->name === 'pending') text-warning
                                    @else text-danger @endif">
                                    {{ $claim->status->name }}
                                </td>
                                <td>{{ $claim->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No claims yet.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


