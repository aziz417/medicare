@extends('layouts.auth')
@section('title', 'Verify Your Email Address')

@section('content')
<div class="w-100">
    <h2 class="h4 mt-0 mb-1">Verify Your Email Address</h2>
    @if (session('resent'))
        <div class="alert alert-success my-3" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif
    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <p class="text-muted">
        Before proceeding, please check your email for a verification link. <br>
        If you did not receive the email,
        <button style="box-shadow: none;" type="submit" class="btn btn-link p-0 m-0 align-baseline">click here</button> to request another.</p>
    </form>

</div>
@endsection
