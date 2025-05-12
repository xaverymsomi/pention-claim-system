@extends('layouts.master')

@section('content')

<div class="card">
   
<div class="card-header">
    {{-- <h4 class="text-bold">
        @switch($status)
            @case('paid') Paid Payouts @break
            @case('pending') Pending Payouts @break
            @default Payouts Management
        @endswitch
    </h4> --}}
    <h4 class="mb-4">
        @if(request()->routeIs('admin.payouts.status') && isset($statusName))
            {{ ucfirst($statusName) }} Payouts
        @else
            All Payouts
        @endif
    </h4>
</div>

    <div class="card-body">
        <table class="table table-bordered table-hover" id="payoutTable">
            <thead class="bg-light">
                <tr>
                    <th>#</th>
                    <th>Farmer</th>
                    <th>Delivery Date</th>
                    <th>Weight (kg)</th>
                    <th>Amount (TZS)</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payouts as $index => $payout)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $payout->delivery->farmer->user->name ?? '-' }}</td>
                        <td>{{ $payout->delivery->delivery_date }}</td>
                        <td>{{ $payout->delivery->weight }} kg</td>
                        <td>{{ number_format($payout->amount, 2) }}</td>
                        <td>
                            @php
                                $status = strtolower($payout->status->name);
                                $badge = $status === 'paid' ? 'success' : 'warning';
                            @endphp
                            <span class="badge badge-{{ $badge }}">{{ ucfirst($status) }}</span>
                        </td>
                        <td>
                            @if($status === 'pending')
                                <button class="btn btn-sm btn-success open-confirm-modal"
                                    data-id="{{ $payout->id }}"
                                    data-name="{{ $payout->delivery->farmer->user->name ?? 'N/A' }}">
                                    Mark as Paid
                                </button>
                            @else
                                <span class="text-muted">Confirmed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No payout records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="confirmForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Confirm Mark as Paid</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span><i class="fas fa-times text-danger"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to mark this payout for <strong id="farmerName"></strong> as paid?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-success">Confirm</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        let confirmUrl = "";

        // Set confirm modal action
        $('.open-confirm-modal').on('click', function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            $('#farmerName').text(name);
            confirmUrl = "{{ route('cooperative.payouts.markAsPaid', '__ID__') }}".replace('__ID__', id);
            $('#confirmForm').attr('action', confirmUrl);
            $('#confirmModal').modal('show');
        });

        // Handle payout confirmation
        $('#confirmForm').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: confirmUrl,
                method: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    $('#confirmModal').modal('hide');
                    if (res.success) {
                        Toast.fire({ icon: 'success', title: res.success });
                        setTimeout(() => location.reload(), 1500);
                    } else if (res.error) {
                        Toast.fire({ icon: 'error', title: res.error });
                    }
                },
                error: function (xhr) {
                    $('#confirmModal').modal('hide');
                    let msg = 'Something went wrong.';
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    Toast.fire({ icon: 'error', title: msg });
                }
            });
        });

        // Initialize DataTable
        $('#payoutTable').DataTable({
            responsive: true,
            pageLength: 10,
        });
    });
</script>
@endsection
