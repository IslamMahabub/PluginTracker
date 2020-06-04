<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Webappick Growth Hacking">
    <meta name="keywords" content="Webappick">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Webappick') }} - @yield('title')</title>

    <!-- Fontfaces CSS-->
    <link href="{{ asset('css/font-face.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-4.7/css/font-awesome.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/font-awesome-5/css/fontawesome-all.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/mdi-font/css/material-design-iconic-font.min.css') }}" rel="stylesheet" media="all">

    <!-- Jquery JS-->
    <script src="{{ asset('vendor/jquery-3.2.1.min.js') }}"></script>
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap-4.1/bootstrap.min.css') }}">
    <!-- Bootstrap JS-->
    <script src="{{ asset('vendor/bootstrap-4.1/popper.min.js') }}" defer></script>
    <script src="{{ asset('vendor/bootstrap-4.1/bootstrap.min.js') }}" defer></script>

    <!-- Datepicker CSS & JS-->
    <script src="{{ asset('vendor/gijgo/js/gijgo.min.js') }}"></script>
    <link href="{{ asset('vendor/gijgo/css/gijgo.min.css') }}" rel="stylesheet" />

    <!-- DataTable-->
    <link rel="stylesheet" type="text/css"  href="{{ asset('vendor/datatable/dataTables.bootstrap4.min.css') }}">
    <script src="{{ asset('vendor/datatable/jquery.dataTables.min.js') }}" type="text/javascript" charset="utf8" ></script>
    <script src="{{ asset('vendor/datatable/dataTables.bootstrap4.min.js') }}" type="text/javascript" charset="utf8" ></script>

    <!-- Vendor CSS-->
    <link href="{{ asset('vendor/animsition/animsition.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/wow/animate.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/css-hamburgers/hamburgers.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/select2/select2.min.css') }}" rel="stylesheet" media="all">
    <link href="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" media="all">
    <!-- Main CSS-->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">

    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>
</head>

<body class="">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="{{ url('/') }}">
                            <h2>Webappick</h2>
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                        <li class="{{ Request::routeIs('home') ? 'active' : '' }}">
                            <a href="{{ url('/') }}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('analytics') ? 'active' : '' }}">
                            <a href="{{ route('analytics') }}">
                                <i class="fas fa-chart-bar"></i>Analytics
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('reasons') ? 'active' : '' }}">
                            <a href="{{ route('reasons') }}">
                                <i class="fas fa-question"></i>Reasons
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('tracker') ? 'active' : '' }}">
                            <a href="{{ route('tracker') }}">
                                <i class="fas fa-search-minus"></i>Plugin Tracking
                            </a>
                        </li>
                        <li class="{{ (Request::Is('responses') || Request::Is('responses/*')) ? 'active' : '' }}">
                            <a href="{{ route('responses.index') }}">
                                <i class="fas fa-reply"></i>Auto Response Message
                            </a>
                        </li>
                        <li class="{{ (Request::Is('settings') || Request::Is('settings/*')) ? 'active' : '' }}">
                            <a href="{{ route('settings.index') }}">
                                <i class="fas fa-cog"></i>Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="{{ url('/') }}">
                    <h2>Webappick</h2>
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="{{ Request::routeIs('home') ? 'active' : '' }}">
                            <a href="{{ url('/') }}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('analytics') ? 'active' : '' }}">
                            <a href="{{ route('analytics') }}">
                                <i class="fas fa-chart-bar"></i>Analytics
                            </a>
                        </li>
                        <li class="{{ Request::routeIs('reasons') ? 'active' : '' }}">
                            <a href="{{ route('reasons') }}">
                                <i class="fas fa-question"></i>Reasons
                            </a>
                        </li>
                        <li class="{{ (Request::Is('tracker') || Request::Is('tracker/*')) ? 'active' : '' }}">
                            <a href="{{ route('tracker') }}">
                                <i class="fas fa-search-minus"></i>Plugin Tracking
                            </a>
                        </li>
                        <li class="{{ (Request::Is('responses') || Request::Is('responses/*')) ? 'active' : '' }}">
                            <a href="{{ route('responses.index') }}">
                                <i class="fas fa-reply"></i>Auto Response Message
                            </a>
                        </li>
                        <li class="{{ (Request::Is('settings') || Request::Is('settings/*')) ? 'active' : '' }}">
                            <a href="{{ route('settings.index') }}">
                                <i class="fas fa-cog"></i>Settings
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <div class="form-header">
                                &nbsp;
                            </div>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="image">
                                            <img src="{{ asset('images/avatar.jpg') }}" alt="{{ Auth::user()->name }}" />
                                        </div>
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">{{ Auth::user()->name }}</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                            <div class="info clearfix">
                                                <div class="image">
                                                    <a href="#">
                                                        <img src="{{ asset('images/avatar.jpg') }}" alt="{{ Auth::user()->name }}" />
                                                    </a>
                                                </div>
                                                <div class="content">
                                                    <h5 class="name">
                                                        <a href="#">{{ Auth::user()->name }}</a>
                                                    </h5>
                                                    <span class="email">{{ Auth::user()->email }}</span>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="{{ route('changePassword') }}">Change password</a>
                                                </div>
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a class="dropdown-item" href="{{ route('logout') }}"
                                                   onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                                    {{ __('Logout') }}
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
                    </div>
                </div>
            </header>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            @yield('content')
            <!-- END MAIN CONTENT-->
        </div>
        <!-- END PAGE CONTAINER-->
    </div>

    <!-- Vendor JS       -->
    <script src="{{ asset('vendor/animsition/animsition.min.js') }}" defer></script>
    <script src="{{ asset('vendor/bootstrap-progressbar/bootstrap-progressbar.min.js') }}" defer></script>
    <script src="{{ asset('vendor/circle-progress/circle-progress.min.js') }}" defer></script>
    <script src="{{ asset('vendor/perfect-scrollbar/perfect-scrollbar.js') }}" defer></script>
    <script src="{{ asset('vendor/chartjs/Chart.bundle.min.js') }}" defer></script>
    <script src="{{ asset('vendor/select2/select2.min.js') }}" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

    <!-- Main JS-->
    <script src="{{ asset('js/main.js') }}" defer></script>

    @yield('script')
</body>

</html>
<!-- end document-->
