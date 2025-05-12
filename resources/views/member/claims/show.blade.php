@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 800px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="h4 font-weight-bold text-dark mb-4">Claim Details</h1>

                <!-- Info Row -->
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
                        }} fw-semibold">
                            {{ ucfirst($claim->status->name) }}
                        </p>
                    </div>
                    <div class="col-md-4">
                        <strong class="text-muted">Submitted On:</strong>
                        <p>{{ $claim->created_at->format('F d, Y') }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if($claim->comments)
                    <div class="mb-4">
                        <h5 class="text-dark">Additional Notes:</h5>
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
                                <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="btn btn-link">View</a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Status History -->
                @if ($claim->statusHistory->count())
                    <div class="mb-4">
                        <h5 class="text-dark">Status History:</h5>
                        <ul class="list-group">
                            @foreach ($claim->statusHistory as $entry)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">{{ ucfirst($entry->status->name) }}</span>
                                        <small class="text-muted ms-5">{{ $entry->created_at->format('M d, Y H:i') }}</small>
                                    </div>
                                    @if($entry->notes)
                                        <p class="text-muted mb-0">{{ $entry->notes }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Back Link -->
                <div class="mt-4">
                    <a href="{{ route('member.claims') }}" class="btn btn-secondary">&larr; Back to My Claims</a>
                </div>
            </div>
        </div>
    </div>
@endsection
