@extends('layouts.master')

@section('content')
<h4 class="mb-4">
    @if(request()->routeIs('admin.deliveries.status') && isset($statusName))
        {{ ucfirst($statusName) }} Deliveries
    @else
        All Deliveries
    @endif
</h4>

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<table class="table table-bordered" id="deliveryTable">
    <thead class="bg-light">
        <tr>
            <th>#</th>
            <th>Farmer</th>
            <th>Weight</th>
            <th>Quality</th>
            <th>Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($deliveries as $delivery)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $delivery->farmer->user->name ?? '-' }}</td>
                <td>{{ $delivery->weight }} kg</td>
                <td>{{ $delivery->quality }}</td>
                <td>{{ $delivery->delivery_date }}</td>
                <td>
                    @php
                        $statusName = strtolower($delivery->status->name);
                        $badgeClass = match ($statusName) {
                            'pending' => 'warning',
                            'approved' => 'success',
                            'rejected' => 'danger',
                            default => 'secondary',
                        };
                    @endphp
                    <span class="badge badge-{{ $badgeClass }}">
                        {{ ucfirst($statusName) }}
                    </span>
                </td>
                <td>
                    @if($delivery->status->name === 'pending')
                        <button class="btn btn-sm btn-success open-approve-modal"
                            data-id="{{ $delivery->id }}">
                            Verify
                        </button>
                    @else
                        <span class="text-success">Verified</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-danger">No deliveries found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form id="approveForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Update Delivery Status</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span><i class="fas fa-times-circle text-info"></i></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="status_id">Select Status</label>
                        <select class="form-control" name="status_id" id="status_id" required>
                            <option value="">-- Choose Status --</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}">{{ ucfirst($status->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" id="deliveryId" name="delivery_id">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success btn-sm">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<!-- Include DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    // Check if the table has data rows (excluding the 'No deliveries found' row)
    const hasData = $('#deliveryTable tbody tr').not(':has(td[colspan])').length > 0;

    if (hasData) {
        $('#deliveryTable').DataTable({
            responsive: true,
            stateSave: true,
            pageLength: 10
        });
    }

    const baseApproveUrl = "{{ route('cooperative.deliveries.approve', ['id' => 'ID_PLACEHOLDER']) }}";

    // Open modal
    $('.open-approve-modal').click(function () {
        const id = $(this).data('id');
        $('#deliveryId').val(id);
        $('#approveModal').modal('show');
    });

    // Submit approve form
    $('#approveForm').submit(function (e) {
        e.preventDefault();
        const id = $('#deliveryId').val();
        const actionUrl = baseApproveUrl.replace('ID_PLACEHOLDER', id);

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: $(this).serialize(),
            success: function (data) {
                $('#approveModal').modal('hide');
                Toast.fire({ icon: 'success', title: data.success });
                setTimeout(() => location.reload(), 1500);
            },
            error: function () {
                Toast.fire({ icon: 'error', title: 'Something went wrong.' });
            }
        });
    });
});
</script>
@endsection
