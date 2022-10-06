@extends('layouts.app')
@section('title', 'Profile')
@section('content')
<header class="page-header">
    <h1 class="page-title">User profile</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-12 col-md-6 mb-md-0">
            <div class="card bg-light {{-- personal-info-card --}}">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 user-actions">
                        <img src="{{ asset($auth->avatar()) }}" width="100" height="100" alt="" class="rounded-500 mr-4">
                        <a href="{{ route('common.profile.edit') }}" class="btn btn-danger rounded-500">Edit Profile</a>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 mt-0 mr-1">{{ $auth->name }}</h5>
                    </div>
                    <p class="text-muted">{{ $auth->email }}</p>
                    <p>{{ $auth->getMeta('user_about') }}</p>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="card mb-md-0">
                <div class="card-header">
                    Contact information
                </div>
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col col-auto">
                            <div class="icon icofont-ui-touch-phone fs-30 text-muted"></div>
                        </div>
                        <div class="col">
                            <div>Mobile</div>
                            {{ $auth->mobile }}
                        </div>
                    </div>
                    <div class="row align-items-center mb-3">
                        <div class="col col-auto">
                            <div class="icon icofont-email fs-30 text-muted"></div>
                        </div>
                        <div class="col">
                            <div>Email</div>
                            {{ $auth->email }}
                        </div>
                    </div>
                    <div class="row align-items-center">
                        <div class="col col-auto">
                            <div class="icon icofont-location-pin fs-30 text-muted"></div>
                        </div>
                        <div class="col">
                            <div>Current Address</div>
                            {{ autop($auth->getMeta('user_address', '~')) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            @includeIf("common.profile.extra.view-{$auth->role}")
        </div>
    </div>
</div>
@endsection
