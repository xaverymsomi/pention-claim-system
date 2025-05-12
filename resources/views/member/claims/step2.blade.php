@extends('layouts.master')

@section('content')
    <div class="container mt-4" style="max-width: 600px;">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="h5 font-weight-bold mb-4 text-dark">Step 2: Upload Documents</h2>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('member.claims.storeStep2') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">National ID <span class="text-danger">*</span></label>
                        <input type="file" name="national_id" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NSSF Card <span class="text-danger">*</span></label>
                        <input type="file" name="nssf_card" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Retirement Letter (optional)</label>
                        <input type="file" name="retirement_letter" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('member.claims.step1') }}" class="btn btn-link">← Back</a>
                        <button type="submit" class="btn btn-primary">Next Step</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
