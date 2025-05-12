@extends('layouts.master')

@section('content')
<div class="card">
    <h4 class="mb-4">
        @if(request()->routeIs('claims.status') && isset($statusName))
            {{ ucfirst($statusName) }} Claims
        @else
            All Claims
        @endif
    </h4>

    <div class="card-body">
        <table class="table table-bordered table-hover" id="claimsTable">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Reference</th>
                    <th>User Name</th>
                    <th>Status</th>
                    <th>Comments</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($claims as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->reference }}</td>
                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                        <td>{{ $item->status->name ?? 'N/A' }}</td>
                        <td>{{ $item->comments ?? '-' }}</td>
                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No available claims found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
