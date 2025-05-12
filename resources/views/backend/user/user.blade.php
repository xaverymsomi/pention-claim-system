@extends('layouts.master')
@section('content')
<style>
    .red{
    color: red;

    }
</style>
<div class="card">

    <div class="card-header">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <h4 class="text-bold">{{$title}} </h4>
            </div>
            <div class="col-md-8 text-right">
                @if(Auth::user()->user_type_id == "1")
                <button id="userBtn" class="btn btn-info btn-sm mr-1">Add New Admin</button>
                @else
                <a href="{{url('new_content_pdf')}}"><button class="btn btn-info btn-sm mr-1"><i class="fa fa-download fa-fw"></i></button></a>
                @endif
            </div>
        </div>
    </div>

    <div class="card-body">

        <table class="table w-100" id="table">
            <thead class="bg-light">
                <th>S/N</th>
                <th>Phone Number</th>
                <th>Full Name</th>
               <th>Email</th>
               <th>Role</th>
                {{-- <th>Action</th> --}}
            </thead>
            <tbody>
                @foreach ($users as $item)
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        @if($item->phone == "")
                        <td>- - -</td>
                        @else
                        <td>{{$item->phone}}</td>
                        @endif
                        <td>{{$item->name}}</td>
                        <td>{{$item->email}}</td>
                        <td>{{$item->role}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>

<div id="userModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="my-modal-title">Title</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-fw fa-times-circle text-info"></i></span>
                </button>
            </div>

            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <div class="form-group">
                        <div class="row">
                            <div class="form-group">
                                <label for="">Name:</label>
                                <input class="form-control" type="text" id="name" name="name" placeholder="Enter name" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="">Phone:</label>
                                <input class="form-control" type="text" id="phone" name="phone" placeholder="Enter Phone" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="">Email Address:</label>
                                <input class="form-control" type="email" id="email" name="email" placeholder="Enter Email" required>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Cancel</button>
                    <input type="hidden" name="action" id="action" value="">
                    <input type="hidden" name="hidden_id" id="hidden_id">
                    <button type="submit" class="btn btn-info btn-sm" id="submitBtn">Wasilisha</button>
                </div>
            </form>
            
        </div>
    </div>
</div>


<script type="text/javascript">

    $(document).ready(function () {
    
    $('#userBtn').on('click', function () {
        // Reset the form fields and set appropriate modal title
        $('#userForm')[0].reset();
        $('.modal-title').text('Add New Admin');
        // Show necessary elements in the modal
        $('#createDiv').show();
        $('#updateDiv').hide();
        $('#submitBtn').show().html('Submit');
        $('#action').val('Generate');
        // Open the modal
        $('#userModal').modal('show');
    });

    $('#userForm').on('submit', function (event) {
        event.preventDefault();
        $("#submitBtn").attr("disabled", true);
        var formData = new FormData(this); // Create FormData object
        var data_url = '';

        if ($('#action').val() == "Generate")
            data_url = "{{ route('user.store') }}";

        // Submit the form data via AJAX
        $.ajax({
            url: data_url,
            method: "POST",
            dataType: "JSON",
            data: formData, // Use FormData to handle file uploads
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if (data.success) {
                    // Handle success response
                    $("#submitBtn").attr("disabled", true);
                    $('#userModal').modal('hide');
                    $('#userForm')[0].reset();
                    var message = data.success;
                    Toast.fire({
                        icon: 'success',
                        title: message,
                    });
                    setTimeout(function () {
                        window.location.reload();
                    }, 4500);
                }
                if (data.errors) {
                    // Handle error response
                    $("#submitBtn").attr("disabled", false);
                    var message = data.errors;
                    Toast.fire({
                        icon: 'info',
                        title: message,
                    });
                }
            }
        });
    });
});
</script>
@endsection
