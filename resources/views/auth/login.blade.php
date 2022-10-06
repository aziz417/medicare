@extends('layouts.auth')
@section('title', 'Login')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Sign in</h2>
    <p class="text-muted">Sign in to access your Account</p>
    @if (session()->has('alert'))
        <div class="alert alert-warning my-3" role="alert">
            {{ session('alert') }}
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}" class="needs-validation" onsubmit="disableSubmitButton(this)" novalidate>
        @csrf
        <div class="form-group">
            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email or Mobile Number" autocomplete="email" autofocus required>
            @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @else
            <div class="invalid-feedback">Please provide a valid email address or mobile number.</div>
            @enderror
        </div>
        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @else
            <div class="invalid-feedback">Enter your account password.</div>
            @enderror
        </div>
        <div class="form-group custom-control custom-switch">
            <input class="custom-control-input" type="checkbox" name="remember" id="remember" checked>
            <label class="custom-control-label" for="remember">Remember me</label>
        </div>
        <div class="actions justify-content-between">
            <button class="btn btn-primary" type="submit"><span class="btn-icon icofont-login mr-2"></span>Sign in</button>
        </div>
    </form>
    <p class="mt-5 mb-1"><a href="{{ route('password.request') }}">Forgot password?</a></p>
    <p>Don't have an account? <a href="{{ route('register') }}">Sign up!</a></p>
</div>
@endsection
