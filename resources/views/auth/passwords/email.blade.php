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
    <title>{{ config('app.name', 'Webappick') }} - Reset Password</title>

    <!-- Bootstrap CSS-->
    <link href="{{ asset('vendor/bootstrap-4.1/bootstrap.min.css') }}" rel="stylesheet" media="all">
    <!-- Vendor CSS-->
    <link href="{{ asset('vendor/animsition/animsition.min.css') }}" rel="stylesheet" media="all">
    <!-- Main CSS-->
    <link href="{{ asset('css/theme.css') }}" rel="stylesheet">

    <!-- Icon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"/>

</head>

<body class="animsition">
<div class="page-wrapper">
    <div class="page-content--bge5">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content">
                    <div class="login-logo">
                        <a href="{{ url('/') }}">
                            Webappick
                        </a>
                    </div>
                    <div class="login-form">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-group">
                                <label for="email">{{ __('E-Mail Address') }}</label>

                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <button type="submit" class="au-btn au-btn--block au-btn--green m-b-20">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Jquery JS-->
<script src="{{ asset('vendor/jquery-3.2.1.min.js') }}" defer></script>
<!-- Bootstrap JS-->
<script src="{{ asset('vendor/bootstrap-4.1/bootstrap.min.js') }}" defer></script>
<!-- Vendor JS       -->
<script src="{{ asset('vendor/animsition/animsition.min.js') }}" defer></script>
<!-- Main JS-->
<script src="{{ asset('js/main.js') }}" defer></script>

</body>

</html>
<!-- end document-->