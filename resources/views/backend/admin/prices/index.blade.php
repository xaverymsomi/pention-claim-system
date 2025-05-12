@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <h4 class="text-bold">{{ $title }}</h4>
            <button id="addPriceBtn" class="btn btn-primary btn-sm">Add Price</button>
        </div>

        <div class="card-body">
            <table class="table table-striped" id="priceTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Price (TZS)</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($price)
                        <tr>
                            <td>1</td>
                            <td>{{ number_format($price->price_per_kg, 2) }}</td>
                            <td>{{ $price->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No price found.</td>
                        </tr>
                    @endif
                </tbody>
                
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="priceModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="priceForm" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Price</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Price (TZS)</label>
                        <input type="number" name="price" step="0.01" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="submit" id="submitPriceBtn" class="btn btn-primary btn-sm">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- DataTables CDN -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#priceTable').DataTable({
                responsive: true,
                pageLength: 10,
                stateSave: true,
                order: [
                    [2, 'desc']
                ],
                columnDefs: [{
                    targets: 0,
                    searchable: false,
                    orderable: false,
                }]
            });

            // Auto-number rows
            table.on('order.dt search.dt draw.dt', function() {
                table.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();

            // Open modal
            $('#addPriceBtn').click(() => {
                $('#priceForm')[0].reset();
                $('#submitPriceBtn').prop('disabled', false);
                $('#priceModal').modal('show');
            });

            // Submit form
            $('#priceForm').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                $('#submitPriceBtn').prop('disabled', true);

                $.ajax({
                    method: "POST",
                    url: "{{ route('admin.prices.store') }}",
                    data: form.serialize(),

                    success: function(data) {
                        if (data.success) {
                            $('#priceModal').modal('hide');
                            $('#priceForm')[0].reset();
                            Toast.fire({
                                icon: 'success',
                                title: data.success
                            });
                            setTimeout(() => window.location.reload(), 3000);
                        }

                        if (data.errors) {
                            Toast.fire({
                                icon: 'info',
                                title: data.errors.join("<br>")
                            });
                            $("#submitBtn").attr("disabled", false);
                        }
                    },
                    error: function() {
                        Toast.fire({
                            icon: 'error',
                            title: 'Server error. Please try again later.'
                        });
                        $("#submitBtn").attr("disabled", false);
                    }
                });
            });
        });
    </script>
@endsection
