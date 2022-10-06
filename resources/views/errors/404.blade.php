<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <x-head-meta/>
    {{-- csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- title --}}
    <title>Not Found | {{ config('app.name', 'Medics') }}</title>
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    {{-- Plugins CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simple-line-icons.css') }}">
    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="public-layout">
    <x-loader/>
    <!-- .main-loader -->
    <div class="page-box">
        <div class="app-container page-404">
            <div class="content-box">
                <div class="content-header">
                    <div class="app-logo">
                        <div class="logo-wrap"><img src="{{ asset('assets/img/logo.svg') }}" alt="{{config('app.name')}}" width="147" height="33" class="logo-img"></div>
                    </div>
                </div>
                <div class="content-body">
                    <div class="w-100 text-center">
                        <div class="display-1 d-flex mb-5 justify-content-center align-items-center">
                            <div class="icon mr-2 icofont-radio-active"></div>
                            <div class="font-weight-normal text-muted mt-0 mb-0">404</div>
                        </div>
                        <h1 class="fs-20 mb-5">Oopps. The page you were looking for doesn't exist.</h1>
                        <a class="btn btn-primary" href="{{ url('/') }}">
                            <span class="btn-icon icofont-home mr-2"></span>Back to home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
