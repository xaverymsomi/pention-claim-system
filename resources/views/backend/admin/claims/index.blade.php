@extends('layouts.master')
@section('content')

    <div class="mb-4">
        <h1 class="h4 font-weight-bold text-dark">All Claims</h1>
        <p class="text-muted">View and manage all pension claims submitted by members.</p>
    </div>

    @if(Auth::user()->user_type_id === 2)
        <div class="alert alert-warning" role="alert">
            Showing only claims assigned to you.
        </div>
    @endif

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-light">
                    <tr>
                        <th scope="col">Reference</th>
                        <th scope="col">Member</th>
                        <th scope="col">Status</th>
                        <th scope="col">Submitted</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($claims as $claim)
                        <tr>
                            <td class="text-primary font-weight-bold">{{ $claim->reference }}</td>
                            <td>{{ $claim->user->name }}</td>
                            <td class="{{
                                $claim->status->id === 2 ? 'text-success font-weight-bold' :
                                ($claim->status->id === 1 ? 'text-warning font-weight-bold' : 'text-danger font-weight-bold')
                            }}">
                                {{ ucfirst($claim->status->name) }}
                            </td>
                            <td>{{ $claim->created_at->format('Y-m-d') }}</td>
                            <td>
                                <a href="{{ route(Route::is('admin.*') ? 'admin.claims.show' : 'staff.claims.show', $claim) }}"
                                   class="btn btn-secondary btn-sm">Review</a>

                                @if(Auth::user()->user_type_id === 1)
                                    @if($claim->assigned_to)
                                        <button disabled class="btn btn-secondary btn-sm">
                                            Assigned: {{ $claim->assignedStaff?->name ?? 'N/A' }}
                                        </button>
                                    @else
                                        <a href="{{ route('admin.claims.assign', $claim) }}"
                                           class="btn btn-sm btn-primary">Assign</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No claims found.</td>
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
