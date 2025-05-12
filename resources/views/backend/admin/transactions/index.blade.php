@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Sales to Buyers</h4>
    </div>

    <div class="card-body">
        <table class="table table-bordered" id="transactionTable">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Cooperative</th>
                    <th>Buyer</th>
                    <th>Weight (kg)</th>
                    <th>Price per KG (TZS)</th>
                    <th>Total (TZS)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $index => $transaction)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $transaction->cooperative->name ?? '-' }}</td>
                        <td>{{ $transaction->buyer->company ?? '-' }}</td>
                        <td>{{ number_format($transaction->weight, 2) }}</td>
                        <td>{{ number_format($transaction->price_per_kg, 2) }}</td>
                        <td>{{ number_format($transaction->total, 2) }}</td>
                        <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr class="no-data">
                        <td colspan="6" class="text-center text-muted">No transactions yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function () {
        const table = $('#transactionTable');
        const hasNoData = table.find('tbody tr.no-data').length > 0;

        if (!hasNoData) {
            table.DataTable({
                responsive: true,
                stateSave: true,
                pageLength: 10,
                language: {
                    searchPlaceholder: "Search transactions...",
                    search: "",
                    emptyTable: "No transactions available."
                }
            });
        }
    });
</script>
@endsection
