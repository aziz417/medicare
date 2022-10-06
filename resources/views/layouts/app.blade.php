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
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-select.min.css') }}">
    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    {{-- Compiled CSS --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/app.css') }}">
    {{-- jQuery --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script>
    @includeIf('partials.dependency')
    @stack('header')
</head>

<body class="{{ $auth->isAdmin() ? 'vertical' : 'horizontal' }}-layout boxed">
    <x-loader/>
    <div class="page-box">
        <div class="app-container">
            @include('partials.header')
            @if( $auth->isAdmin() )
            @include('partials.sidebar')
            @else
            @include('partials.topbar')
            @endif

            <main class="main-content mb-1">
                <div class="app-loader"><i class="icofont-spinner-alt-4 rotate"></i></div>
                <div class="h-100 main-content-wrap">
                    @includeIf('partials.messages')
                    @yield('content')
                </div>
            </main>
            @include('partials.footer')
            <div class="content-overlay"></div>
            @stack('container')
        </div>
    </div>
    @stack('modal')
    <div id="start-call"></div>

    <script src="{{ asset('assets/js/jquery-migrate-1.4.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.typeahead.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.barrating.min.js') }}"></script>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    {{-- Compiled JS --}}
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @stack('script')
    @stack('footer')
</body>

</html>
