@extends('layouts.app')
@section('title', 'View Doctors')

@push('header')
<style type="text/css">
    img.card-img-top {
        max-height: 120px;
        min-height: 100px;
        background-color: #eef;
    }
    .rounded-50p {
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">Doctor's Profile</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-12 col-md-6 mb-md-0">
            <div class="card bg-light personal-info-card">
                <img src="{{ asset('assets/content/bg-card.jpg') }}" class="card-img-top" alt="Topbar">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 user-actions">
                        <img src="{{ asset($doctor->avatar()) }}" width="100" height="100" alt="" class="rounded-50p mr-4">
                        <a href="{{ route('user.doctors.booking', $doctor->id) }}" class="btn btn-danger rounded-500">Book Appointment</a>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 mt-0 mr-1">{{ $doctor->name }}</h5>
                    </div>
                    <p class="text-muted">
                        {{ $doctor->getMeta('user_designation') }} <br>
                        <strong>{{ $doctor->department->name ?? '~' }}</strong> <br>
                        <p>{{ $doctor->getMeta('user_specialization') }}</p>
                    </p>
                    <p>{{ $doctor->getMeta('user_about') }}</p>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <div class="row">
                <div class="col col-12 col-md-6">
                    <div class="card mb-md-0">
                        <div class="card-header">
                            Contact information
                        </div>
                        <div class="card-body">
                            {{-- <div class="row align-items-center mb-3">
                                <div class="col col-auto">
                                    <div class="icon icofont-ui-touch-phone fs-30 text-muted"></div>
                                </div>
                                <div class="col">
                                    <div>Mobile</div>
                                    {{ $doctor->mobile }}
                                </div>
                            </div> --}}
                            <div class="row align-items-center mb-3">
                                <div class="col col-auto">
                                    <div class="icon icofont-email fs-30 text-muted"></div>
                                </div>
                                <div class="col">
                                    <div>Email</div>
                                    {{ $doctor->email }}
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col col-auto">
                                    <div class="icon icofont-location-pin fs-30 text-muted"></div>
                                </div>
                                <div class="col">
                                    <div>Current Address</div>
                                    {{ autop($doctor->getMeta('user_address', '~')) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card mb-0 ">
                <div class="card-header">Appointment Charges</div>
                <div class="card-body">
                    <h6 class="mt-0 mb-0">New Appointment Booking</h6>
                    {{-- → ⇒ --}}
                    <p>⇒ {{ inCurrency($doctor->getCharge('booking')->amount) }}</p>
                    <h6 class="mt-0 mb-0">Re-Appointment (within 30 days)</h6>
                    <p>⇒ {{ inCurrency($doctor->getCharge('reappoint')->amount) }}</p>
                    <h6 class="mt-0 mb-0">Report Showing</h6>
                    <p>⇒ {{ inCurrency($doctor->getCharge('report')->amount) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection