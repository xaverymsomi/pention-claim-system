@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 font-weight-bold mb-4 text-dark">Step 1: Claim Details</h2>

                <form method="POST" action="{{ route('member.claims.storeStep1') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" value="{{ Auth::user()->name }}" readonly
                               class="form-control bg-light text-dark">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" value="{{ Auth::user()->email }}" readonly
                               class="form-control bg-light text-dark">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Additional Notes (optional)</label>
                        <textarea name="notes" rows="4" class="form-control"
                                  placeholder="You may provide additional info about your claim...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('dashboard') }}" class="btn btn-link">Cancel</a>
                        <button type="submit" class="btn btn-primary">Next Step</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
