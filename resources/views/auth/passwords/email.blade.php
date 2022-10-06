@extends('layouts.auth')
@section('title', 'Reset Password')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Reset Password</h2>
    <p class="text-muted">Enter you email address to reset your password</p>
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <form method="POST" action="{{ route('password.email') }}" class="needs-validation" novalidate>
        @csrf
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email address" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @else
            <div class="invalid-feedback">Please provide a valid email address.</div>
            @enderror
        </div>
        <div class="actions justify-content-between">
            <button class="btn btn-primary" type="submit"><span class="btn-icon icofont-lock mr-2"></span>Reset Password</button>
        </div>
    </form>
    <p class="mt-5 mb-1"><a href="{{ route('login') }}">Login Instead</a></p>
</div>
@endsection
