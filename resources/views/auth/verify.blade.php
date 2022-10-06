@extends('layouts.auth')
@section('title', 'Verify Yourself')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Verify Mobile or Email Address</h2>
    <p class="text-muted">Please confirm your mobile number or email address before continuing.</p>
    @if (session('resent-code'))
        <div class="alert alert-success my-3" role="alert">
            {{ __('A fresh verification code has been sent to your mobile number.') }}
        </div>
    @endif
    @if (session('resent'))
        <div class="alert alert-success my-3" role="alert">
            {{ __('A fresh verification link has been sent to your email address, click the link to verify your email address.') }}
        </div>
    @endif
    <form method="POST" action="{{ route('verification.mobile.verify') }}" class="needs-validation" autocomplete="off" novalidate>
        @csrf
        <div class="form-group">
            <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" placeholder="OTP" maxlength="4" minlength="4" required>
            @error('otp')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div class="actions justify-content-between">
            <button class="btn btn-primary" type="submit"><span class="btn-icon icofont-login mr-2"></span>Verify</button>
            <form class="mt-4" action="{{ route('verification.mobile.resend') }}" method="POST">
                @csrf <button style="box-shadow: none;" type="submit" class="ml-3 btn btn-outline-info">Resend Code</button></p>
            </form>
        </div>
    </form>
    
    <form class="mt-3" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <p class="text-muted">
            Or verify your email address instead of mobile verification, for getting a new verification email 
        <button style="box-shadow: none;" type="submit" class="btn btn-link p-0 m-0 align-baseline">click here</button></p>
    </form>
</div>

@endsection
