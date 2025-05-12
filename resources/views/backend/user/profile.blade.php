@extends('layouts.master')
@section('content')

<body onload="myFunction()">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-with-nav">
                <div class="card-header">
                    <div class="row row-nav-line">
                        <ul class="nav nav-tabs nav-line nav-color-secondary w-100 pl-3" role="tablist">
                            <li class="nav-item"> <a class="nav-link active" onclick="personalFunction()" data-toggle="tab" href="#home" aria-selected="true">Personal Profile</a> </li>
                            <li class="nav-item"> <a class="nav-link" onclick="passwordFunction()" data-toggle="tab" href="#password" role="tab" aria-selected="true">Password</a> </li>
                        </ul>
                    </div>

                    @if (session('errors'))
                    <div class="alert alert-warning text-center text-small mt-2">
                        <button class="close" type="button" data-dismiss="alert">&times;</button>
                        @foreach ($errors->all() as $error)
                        <small>{{$error}}</small>
                        @endforeach
                    </div>
                    @endif
    
                    @if (session('success'))
                    <div class="alert alert-success">
                        <button class="close" type="button" data-dismiss="alert">&times;</button>
                        {{ session('success') }}
                    </div>
                    @endif
                </div>
                <div class="card-body">
    
                    <div class="tab" id="personalDiv">
                         
                        <form action="{{route('users.profile_update')}}" method="POST" enctype="multipart/form-data">
                            @csrf
        
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Full Name:</label>
                                        <input type="text" name="name" class="form-control" value="{{$personal_details->name}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Role:</label>

                                        @if(($personal_details->role == "") || ($personal_details->role == "NULL"))
                                            <input type="text" name="role" readonly class="form-control" value="Admin">
                                        @else
                                            <input type="text" name="role" readonly class="form-control" value="{{$personal_details->role}}">
                                        @endif
                                    </div>
                                </div>
                            </div>
            
                           
            
                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="">Address:</label>
                                        <input type="text" name="address" class="form-control" value="{{$personal_details->address}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="">Phone Number:</label>
                                        <input type="text" name="mawasiliano" class="form-control" value="{{$personal_details->mawasiliano}}">
                                    </div>
                                </div>
                            </div> --}}
        
                            {{-- <div class="form-group">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label for="">profile picture:</label>
                                        <input class="form-control" accept="images/*.png,.png,.jpg,.jpeg,.gif,.tiff" type="file" name="profile_photo_path">
                                    </div>
                                </div>
                            </div> --}}
        
                            <div class="form-group">
                                <div class="text-right mb-0">
                                    <button type="reset" class="btn btn-warning mr-3">reset</button>
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>
                            </div>
        
                        </form>
                    </div>
    
                    <div class="tab" id="passwordDiv">
                        <form action="{{route('users.password_update')}}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="">Old Password:</label>
                                <input id="old_password" name="old_password" type="password" placeholder="Enter old password"
                                class="form-control @error('old_password') is-invalid @enderror">
                            </div>
        
                            <div class="form-group">
                                <label>New Password:</label>
                                <input id="password" name="password" type="password" placeholder="Enter new password"
                                class="form-control @error('password') is-invalid @enderror">                             
                            </div>
        
                            <div class="form-group">
                                <label>Confirm Password:</label>
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                    placeholder="Enter confirm password"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            </div>

                            <div class="form-group">
                                <div class="text-right mb-0">
                                    <button class="btn btn-warning mr-3">reset</button>
                                    <button type="submit" class="btn btn-info">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
    
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-header" style="background-image: url('../assets/img/blogpost.jpg')">
                    <div class="profile-picture">
                        <div class="avatar avatar-xl">
                            @if(auth()->user()->profile_photo_path == "")
                                <img src="{{asset('test/images/profile.png')}}" alt="..." class="avatar-img rounded-circle">
                            @else
                                <img src="{{url('test/uploads/images/'.$personal_details->profile_photo_path)}}" alt="..." class="avatar-img rounded-circle">
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="user-profile text-center">
                        <div class="name">{{auth()->user()->name}}</div>
                        <div class="job">{{auth()->user()->user_type_id}}</div>
                        
                        <div class="social-media">
                            <a class="btn btn-info btn-email btn-sm btn-link"> 
                                <span class="btn-label just-icon"><i class="flaticon-envelope"></i> </span>
                            </a>{{$personal_details->email}}
                        </div>
    
                        <div class="view-profile">
                            <a class="btn btn-info w-100" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                <i class="fas fa-fw fa-power-off"></i> &nbsp;&nbsp; {{ __('Log out') }}
                            </a>
    
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>   
</body>

<script>
    
    function myFunction(){
        $('#personalDiv').show();
        $('#passwordDiv').hide();
    }

    function passwordFunction(){
        $('#personalDiv').hide();
        $('#passwordDiv').show();
    }

    function personalFunction(){
        $('#personalDiv').show();
        $('#passwordDiv').hide();
    }

</script>
@endsection