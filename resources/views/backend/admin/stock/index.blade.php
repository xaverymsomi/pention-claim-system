@extends('layouts.master')

@section('content')
<h4 class="mb-4">Cashew Stock</h4>

<table class="table table-bordered" id="stockTable">
    <thead class="bg-light">
        <tr>
            <th>#</th>
            <th>Farmer</th>
            <th>Weight (kg)</th>
            <th>Available Weight (kg)</th>
            <th>Quality</th>
            <th>Delivery Date</th>
            <th>Added Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($stocks as $stock)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $stock->delivery->farmer->user->name ?? '-' }}</td>
                <td>{{ number_format($stock->weight, 2) }}</td>
                <td>{{ number_format($stock->available_weight, 2) }}</td>
                <td>{{ $stock->delivery->quality }}</td>
                <td>{{ $stock->delivery->delivery_date }}</td>
                <td>{{ $stock->delivery->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No stock available yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#stockTable').DataTable({
            responsive: true,
            stateSave: true,
        });
    });
</script>
@endsection
