@extends('layouts.auth')
@section('title', 'Confirm Password')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Confirm Password</h2>
    <p class="text-muted">Please confirm your password before continuing.</p>
    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf
        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required autocomplete="current-password">
            @error('password')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="actions justify-content-between">
            <button class="btn btn-primary" type="submit"><span class="btn-icon icofont-login mr-2"></span>Sign in</button>
        </div>
    </form>
    <p class="mt-5 mb-1"><a href="{{ route('password.request') }}">Forgot password?</a></p>
</div>

@endsection
