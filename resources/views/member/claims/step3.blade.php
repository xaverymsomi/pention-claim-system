@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 font-weight-bold mb-4 text-dark">Step 3: Review & Submit</h2>

                <div class="mb-4">
                    <div class="mb-3">
                        <h4 class="fw-semibold text-dark">Your Notes:</h4>
                        <p class="text-muted">{{ $step1['notes'] ?? 'None' }}</p>
                    </div>

                    <div class="mb-3">
                        <h4 class="fw-semibold text-dark">Uploaded Documents:</h4>
                        <ul class="list-group">
                            @foreach($step2 as $key => $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ ucwords(str_replace('_', ' ', $key)) }}:
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="btn btn-link">View</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <form method="POST" action="{{ route('member.claims.submit') }}">
                    @csrf
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('member.claims.step2') }}" class="btn btn-link">← Back</a>
                        <button type="submit" class="btn btn-primary">Submit Claim</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
