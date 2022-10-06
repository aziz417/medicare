@extends('layouts.auth')
@section('title', 'Register')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Confirmation!</h2>
    <p class="text-muted">Confirm your information to open your account!</p>
    <form method="POST" action="{{ route('auth.migrate.register') }}" class="needs-validation" onsubmit="disableSubmitButton(this)" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token ?? null }}">
        <div class="form-group">
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $userdata['name'] ?? old('name') }}" placeholder="Name" required autocomplete="name" autofocus>
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $userdata['email'] ?? old('email') }}" placeholder="Email" required autocomplete="email">
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="mobile" type="text" class="form-control @error('mobile') is-invalid @enderror" name="mobile" value="{{ $userdata['mobile'] ?? old('mobile') }}" placeholder="Mobile Number" required autocomplete="mobile">
            @error('mobile')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <span class="text-muted">You have to verify your mobile number after submit!</span>
        </div>

        <div class="form-group">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="new-password" minlength="8" placeholder="Set Password">
            <span class="invalid-feedback" role="alert">Password at least 8 character long.</span>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="actions justify-content-between">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
    <p class="mt-5">To create a fresh/new account <a href="{{ route('register') }}">click here!</a></p>
</div>
@endsection
