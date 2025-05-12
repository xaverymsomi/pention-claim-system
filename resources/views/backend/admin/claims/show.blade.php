@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 font-weight-bold mb-4 text-dark">Claim Review</h1>

                <!-- Reference / Status / User -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <strong class="text-muted">Reference:</strong>
                        <p class="text-primary">{{ $claim->reference }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong class="text-muted">Status:</strong>
                        <p class="{{
                            $claim->status->id === 2 ? 'text-success' :
                            ($claim->status->id === 1 ? 'text-warning' : 'text-danger')
                        }} fw-semibold">{{ ucfirst($claim->status->name) }}</p>
                    </div>
                    <div class="col-md-4">
                        <strong class="text-muted">Submitted By:</strong>
                        <p>{{ $claim->user->name }} ({{ $claim->user->email }})</p>
                    </div>
                </div>

                <!-- Notes -->
                @if($claim->comments)
                    <div class="mb-4">
                        <h5 class="text-dark">Member Notes:</h5>
                        <p class="alert alert-secondary">{{ $claim->comments }}</p>
                    </div>
                @endif

                <!-- Documents -->
                <div class="mb-4">
                    <h5 class="text-dark">Uploaded Documents:</h5>
                    <ul class="list-group mb-3">
                        @foreach ($claim->documents as $doc)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ ucwords(str_replace('_', ' ', $doc->document_type)) }}
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="btn btn-link">View</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Status History -->
                @if($claim->statusHistory->count())
                    <div class="mb-4">
                        <h5 class="text-dark">Status History:</h5>
                        <ul class="list-group">
                            @foreach ($claim->statusHistory as $entry)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">{{ ucfirst($entry->status->name) }}  </span>
                                        <small class="text-muted">{{ $entry->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    @if($entry->notes)
                                        <p class="text-muted mb-0">{{ $entry->notes }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Review Form -->
                <div class="mt-4 border-top pt-3">
                    <h5 class="text-dark">Update Claim Status:</h5>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route(Route::is('admin.*') ? 'admin.claims.updateStatus' : 'staff.claims.updateStatus', $claim) }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">New Status</label>
                            <select name="status_id" required class="form-control">
                                <option value="">-- Select --</option>
                                <option value="2">Approve</option>
                                <option value="3">Reject</option>
                                <option value="4">Mark as Under Review</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes (optional)</label>
                            <textarea name="notes" rows="3" class="form-control" placeholder="Add optional comments for the member..."></textarea>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
