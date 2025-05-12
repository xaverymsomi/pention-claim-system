@extends('layouts.master')

@section('content')
    <div class="container mt-4">
        <div class="mb-4">
            <h1 class="h4 fw-bold text-dark">Welcome back, Admin {{ Auth::user()->name }} 👋</h1>
            <p class="text-muted">Here is an overview of pension claim activity.</p>
        </div>

        <!-- Summary Cards -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3 mb-4">
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Total Claims</h5>
                        <p>{{ $totalClaims }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Approved</h5>
                        <p>{{ $approvedClaims }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Rejected</h5>
                        <p>{{ $rejectedClaims }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Pending</h5>
                        <p>{{ $pendingClaims }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Claims Overview Chart -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Monthly Claims Overview</h5>
                <canvas id="claimsChart"></canvas>
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
                            <th>Claim ID</th>
                            <th>User</th>
                            <th>Submitted On</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($recentClaims as $claim)
                            <tr>
                                <td>{{ $claim->reference }}</td>
                                <td>{{ $claim->user->name ?? 'N/A' }}</td>
                                <td>{{ $claim->created_at->format('Y-m-d H:i') }}</td>
                                <td class="{{ $claim->status->id == 2 ? 'text-success' : ($claim->status->id == 1 ? 'text-warning' : 'text-danger') }} fw-semibold">
                                    {{ ucfirst($claim->status->name ?? 'Unknown') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.claims.show', $claim) }}" class="btn btn-link">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No recent claims found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('claimsChart').getContext('2d');
        const claimsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthlyClaims->keys()) !!},
                datasets: [{
                    label: 'Monthly Claims',
                    data: {!! json_encode($monthlyClaims->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Claims'
                        }
                    }
                }
            }
        });
    </script>
@endsection

