@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Sign up</h2>
    <p class="text-muted">Sign up to create your Account</p>
    <form method="POST" action="{{ route('register') }}" class="needs-validation" onsubmit="disableSubmitButton(this)" novalidate>
        @csrf
        <div class="form-group">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" placeholder="Name" required autocomplete="name" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile Number" required autocomplete="mobile">
            @error('mobile')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="form-group">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
        </div>

        <div class="actions justify-content-between">
            <button class="btn btn-primary" type="submit"><span class="btn-icon icofont-plus mr-2"></span>Sign up</button>
        </div>
    </form>
    <p class="mt-5">Have an account? <a href="{{ route('login') }}">Sign in!</a></p>
</div>
@endsection
