@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Reset Password</h2>
    <p class="text-muted">Set your account password</p>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" placeholder="Email address" required autocomplete="email" autofocus>
            @error('email')
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
            <button class="btn btn-primary" type="submit"><span class="btn-icon icofont-lock mr-2"></span>Reset Password</button>
        </div>
    </form>
</div>
@endsection
