<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <x-head-meta/>
    {{-- csrf token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- title --}}
    <title>@yield('title') | {{ config('app.name', 'Medics') }}</title>
    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    {{-- Plugins CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simple-line-icons.css') }}">
    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @includeIf('partials.dependency')
    @stack('header')
</head>

<body class="public-layout">
    <x-loader/>
    <div class="page-box">
        <div class="app-container page-sign-in">
            <div class="content-box">
                <div class="content-header">
                    <div class="app-logo">
                        <div class="logo-wrap"><img src="{{ asset('assets/img/logo.svg') }}" alt="{{config('app.name')}}" width="147" height="33" class="logo-img"></div>
                    </div>
                </div>
                <div class="content-body">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    @stack('footer')
</body>

</html>