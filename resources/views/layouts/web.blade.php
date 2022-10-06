<!DOCTYPE html>
<html lang="en">
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
    {{-- Compiled CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">
    {{-- Web CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/web.css') }}">
    {{-- jQuery --}}
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>

    @stack('header')
</head>

<body class="horizontal-layout web-layout boxed">
    <div class="page-box">
        <div class="app-container">
            <div class="web-nav">
                <div class="left-part"></div>
                <div class="middle-part"><a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo.svg') }}" alt="{{ config('app.name') }}" width="145" height="35" class="logo-img"></a></div>
                <div class="right-part"></div>
            </div>
            <main class="main-content mb-1">
                <div class="h-100 web-wrap">
                    @includeIf('partials.messages')
                    @yield('content')
                </div>
            </main>
            @include('partials.footer')
            <div class="content-overlay"></div>
        </div>
    </div>
    @stack('footer')
</body>

</html>