@extends('layouts.master')

@section('content')
    <div class="mb-4">
        <h1 class="h4 font-weight-bold text-dark">My Claims</h1>
        <p class="text-muted">Here’s a list of all your submitted pension claims.</p>
    </div>

    <a href="{{ route('member.claims.step1') }}" class="btn btn-primary">+ Add Claim</a>

    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                    <tr>
                        <th>Reference</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($claims as $claim)
                        <tr>
                            <td class="fw-semibold text-primary">{{ $claim->reference }}</td>
                            <td class="{{
                                $claim->status->id === 1 ? 'text-success' :
                                ($claim->status->id === 2 ? 'text-warning' : 'text-danger')
                            }}">
                                {{ ucfirst($claim->status->name) }}
                            </td>
                            <td>{{ $claim->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route('member.claims.show', $claim) }}" class="btn btn-link text-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No claims found.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $claims->links() }}
    </div>
@endsection

