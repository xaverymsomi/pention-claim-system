@php use Illuminate\Support\Facades\Auth; @endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NSSF Pensions Claim System</title>

    <!-- CSS Dependencies -->
    <link rel="stylesheet" href="{{ asset('test/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/atlantis.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/my_style.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('test/css/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Font Loader -->
    <script src="{{ asset('test/js/webfont.min.js') }}"></script>
    <script>
        WebFont.load({
            google: { families: ["Poppins:300,400,700,900"] },
            custom: {
                families: ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ["{{ asset('test/css/fonts.min.css') }}"]
            },
            active: () => sessionStorage.fonts = true
        });
    </script>

    <style>
        @font-face {
            font-family: 'crimsonpro', serif;
            src: url("{{ url('/test/fonts/crimsonpro.woff2') }}");
        }
    </style>

    <!-- JavaScript Dependencies -->
    <script src="{{ asset('test/js/jquery.3.2.1.min.js') }}"></script>
    <script src="{{ asset('test/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('test/js/popper.min.js') }}"></script>
    <script src="{{ asset('test/js/datatables_new.min.js') }}"></script>
    <script src="{{ asset('test/js/highcharts.js') }}"></script>
    <script src="{{ asset('test/js/sweetalert2.all.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- SweetAlert Toast -->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    </script>
</head>

<body>
    <div class="wrapper">
        <!-- Header -->
        <div class="main-header">
            <div class="logo-header" data-background-color="white">
                <a href="{{ url('dashboard') }}" class="logo">
                    <img src="{{ asset('image/nssf_log1.jpg') }}" width="60" alt="logo" class="navbar-brand">
                </a>
                <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse">
                    <span class="navbar-toggler-icon"><i class="icon-menu"></i></span>
                </button>
                <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                <div class="nav-toggle">
                    <button class="btn btn-toggle toggle-sidebar"><i class="icon-menu"></i></button>
                </div>
            </div>

            <nav class="navbar navbar-header navbar-expand-lg" data-background-color="white">
                <div class="container-fluid">
                    <div class="collapse" id="search-nav">
                        <form class="navbar-left navbar-form nav-search mr-md-3" action="/search" role="search">

                            @csrf

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button type="submit" class="btn btn-search pr-1">
                                        <i class="fas fa-search search-icon"></i>
                                    </button>
                                </div>
                                <input type="search" placeholder="Search..." class="form-control" name="q"
                                    style="background: #FFFFFF;">
                            </div>
                        </form>
                    </div>

                    <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
                        <li class="nav-item dropdown hidden-caret">
                            <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="{{ URL('contents') }}">
                                <div class="avatar-sm">
                                    <img src="{{ asset('test/images/profile.png') }}" alt="photo" class="avatar-img rounded-circle">
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-user animated fadeIn">
                                <div class="dropdown-user-scroll scrollbar-outer">
                                    <li>
                                        <div class="user-box">
                                            <div class="avatar-lg">
                                                <img src="{{ asset('test/images/profile.png') }}" alt="profile" class="avatar-img rounded">
                                            </div>
                                            <div class="u-text">
                                                <p class="text-muted">{{ auth()->user()->email }}</p>
                                                <a href="{{ url('users_profile') }}" class="btn btn-xs btn-info btn-sm">Profile</a>
                                            </div>
                                        </div>
                                    </li>
                                    <div class="dropdown-divider"></div>
                                    <li>
                                        <a class="dropdown-item" href="{{ URL('contents') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-fw fa-power-off"></i> {{ __('Log Out') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </div>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>

        <!-- Sidebar -->
        <div class="sidebar sidebar-style-2">
            <div class="sidebar-wrapper scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-primary">


                        @php $userType = Auth::user()->user_type_id; @endphp


                        @if ($userType == 1)


                            <li class="nav-item">
                                <a href="{{ url('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="collapse" href="#claims">
                                    <i class="fas fa-file-alt"></i>
                                    <p>Claims</p><span class="caret"></span>
                                </a>
                                <div class="collapse" id="claims">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('admin.claims') }}"><span class="sub-item">All Claims</span></a></li>
                                        <li><a href="{{ route('admin.pending.claims') }}"><span class="sub-item">Pending Claims</span></a></li>
                                        <li><a href="{{ route('admin.approved.claims') }}"><span class="sub-item">Approved Claims</span></a></li>
                                        <li><a href="{{ route('admin.rejected.claims') }}"><span class="sub-item">Rejected Claims</span></a></li>
                                        <li><a href="{{ route('admin.under_review.claims') }}"><span class="sub-item">Under Review Claims</span></a></li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}">
                                    <i class="fas fa-user-alt"></i>
                                    <p>Manage Users</p>
                                </a>
                            </li>
                        @elseif ($userType == 2)
                            <li class="nav-item">
                                <a href="{{ url('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="collapse" href="#myclaimsModule">
                                    <i class="fas fa-file-alt"></i>
                                    <p>My Claims</p><span class="caret"></span>
                                </a>
                                <div class="collapse" id="myclaimsModule">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('member.claims') }}"><span class="sub-item">All Claims</span></a></li>
                                        <li><a href="{{ route('member.pending.claims') }}"><span class="sub-item">Pending Claims</span></a></li>
                                        <li><a href="{{ route('member.approved.claims') }}"><span class="sub-item">Approved Claims</span></a></li>
                                        <li><a href="{{ route('member.rejected.claims') }}"><span class="sub-item">Rejected Claims</span></a></li>
                                        <li><a href="{{ route('member.under_review.claims') }}"><span class="sub-item">Under Review Claims</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @elseif ($userType == 3)

                            <li class="nav-item">
                                <a href="{{ url('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a data-toggle="collapse" href="#claimsModule">
                                    <i class="fas fa-file-alt"></i>
                                    <p>Claims</p><span class="caret"></span>
                                </a>
                                <div class="collapse" id="claimsModule">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('staff.available.claims') }}"><span class="sub-item">All Claims</span></a></li>
                                        <li><a href="{{ route('claims.status', ['status' => 'pending']) }}"><span class="sub-item">Pending Claims</span></a></li>
                                        <li><a href="{{ route('claims.status', ['status' => 'approved']) }}"><span class="sub-item">Approved Claims</span></a></li>
                                        <li><a href="{{ route('claims.status', ['status' => 'rejected']) }}"><span class="sub-item">Rejected Claims</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Panel -->
        <div class="main-panel">
            <div class="content">
                <div class="page-inner">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Extra JS -->
    <script src="{{ asset('test/js/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('test/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('test/js/jquery.ui.touch-punch.min.js') }}"></script>
    <script src="{{ asset('test/js/jquery.scrollbar.min.js') }}"></script>
    <script src="{{ asset('test/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('test/js/atlantis.min.js') }}"></script>
</body>
</html>
