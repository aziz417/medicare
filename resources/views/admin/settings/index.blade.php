@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<header class="page-header">
    <h1 class="page-title">Application Settings</h1>

    <div class="settings-links">
        <div class="btn-group btn-group-sm">
            <a title="General Setting" href="{{ route('admin.settings') }}" class="btn btn-dark">General</a>
            <a title="Email Setting" href="{{ route('admin.settings', 'email') }}" class="btn btn-primary">Email</a>
            <a title="SMS Setting" href="{{ route('admin.settings', 'sms') }}" class="btn btn-success">SMS</a>
            <a title="Payment Setting" href="{{ route('admin.settings', 'payment') }}" class="btn btn-warning">Payment</a>
        </div>
    </div>
</header>
@includeIf("admin.settings.{$type}")
@endsection
