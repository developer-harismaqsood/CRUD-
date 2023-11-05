<!doctype html>
<html lang="en" dir="ltr" data-color-theme="dark">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Company CRM')</title>
    <base href="http://localhost/crm_test_haris/">
    <!-- <base href="https://www.mdcdev.xyz/"> -->

    <!-- Global stylesheets -->
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> -->
    <link href="{{asset('css/custom.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/fonts/inter/inter.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/icons/phosphor/styles.min.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/ltr/all.min.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/css/custom.css')}}" id="stylesheet" rel="stylesheet" type="text/css">
    <!-- Global stylesheets -->

    <!-- /core JS files -->
    <script src="{{asset('assets/demo/demo_configurator.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/bootstrap.bundle.min.js')}}"></script>
    <!-- /core JS files -->


    @yield('stylesheet')


    <style type="text/css">
        .error-color{
            color: orange;
        }
    </style>
</head>
<body>


<div id="app">    
    <div class="navbar navbar-dark navbar-static py-2">
    <div class="container-fluid">
         @auth()
        <div class="d-flex d-lg-none me-2">
            <button type="button" class="navbar-toggler sidebar-mobile-main-toggle rounded-pill">
                <i class="ph-list"></i>
            </button>
        </div>
        @endauth
        <div class="navbar-brand">
            <a href="{{url('/')}}" class="d-inline-flex align-items-center">
                <span class="logo-name">Company CRM</span>
            </a>
        </div>
        <div class="d-flex justify-content-end align-items-center ms-auto">
            @auth()
            <ul class="nav flex-row justify-content-end order-1 order-lg-2">
                <li class="nav-item nav-item-dropdown-lg dropdown ms-lg-2">
                    <a href="#" class="navbar-nav-link align-items-center rounded-pill p-1" data-bs-toggle="dropdown">
                        <div class="status-indicator-container">
                            <img src="assets/images/demo/users/face11.jpg" class="w-32px h-32px rounded-pill" alt="">
                            <span class="status-indicator bg-success"></span>
                        </div>
                        <span class="d-none d-lg-inline-block mx-lg-2 ">{{ $meDetail->name ?? '' }}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a href="my-account/password-setting" class="dropdown-item">
                            <i class="ph-user-circle me-2"></i>
                            Change password
                        </a>
                        <a href="{{ route('logout') }}" 
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
                            <div class="d-flex align-items-center mx-md-1">
                                <i class="ph-user-circle-plus"></i>
                                <span class="d-md-inline-block ms-2">Logout</span>
                            </div>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
            </ul>
                @endauth
            <ul class="navbar-nav flex-row">
                @guest
                @if(Request::url() != url('/login'))
                <li class="nav-item">
                    <a href="{{url('/login')}}" class="navbar-nav-link navbar-nav-link-icon rounded ms-1">
                        <div class="d-flex align-items-center mx-md-1">
                            <i class="ph-user-circle"></i>
                            <span class="d-none d-md-inline-block ms-2">Login</span>
                        </div>
                    </a>
                </li>
                @endif
                @endguest
            </ul>
        </div>
    </div>
</div>

<!-- container -->
    <!-- Page content -->
        @yield('content')
<!-- container -->

</div>

    @yield('javascript')

        <!-- Theme JS files -->

    <script src="{{asset('assets/js/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/app.js?v=').time() }}"></script>
    
    <script src="{{asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>

    <script src="{{asset('assets/js/app.js') }}"></script>

    <script src="{{asset('assets/demo/pages/datatables_basic.js') }}"></script>

    <!-- Theme JS files -->

</body>
</html>
