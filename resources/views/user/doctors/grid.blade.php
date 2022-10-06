@extends('layouts.app')
@section('title', 'Doctors')

@push('header')
<style type="text/css">
    img.card-img-top {
        max-height: 100px;
        min-height: 80px;
        background-color: #eef;
    }
    .rounded-50p {
        border-radius: 50%;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">Doctors</h1>
</header>
<div class="page-content">
    <div class="row">
        @foreach($doctors as $doctor)
        <div class="col-12 col-lg-3 col-md-6">
            <div class="card bg-light personal-info-card">
                <img src="{{ asset('assets/content/bg-card.jpg') }}" class="card-img-top" alt="Topbar">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 user-actions">
                        <img src="{{ asset($doctor->avatar()) }}" width="100" height="100" alt="" class="rounded-50p mr-4">
                        <a href="{{ route('user.doctors.show', $doctor->id) }}" class="btn btn-danger rounded-500">Profile</a>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 mt-0 mr-1">{{ $doctor->name }}</h5>
                    </div>
                    <p class="text-muted">{{ $doctor->getMeta('user_designation') }} <br>
                        <strong>{{ $doctor->department->name }}</strong></p>
                    <p>{{ word_limit($doctor->getMeta('user_about', '')) }}</p>
                    <a href="{{ route('user.doctors.booking', $doctor->id) }}" class="btn btn-primary">Book Appointment</a>
                </div>
            </div>
        </div>
        @endforeach

        <div class="col-md-12 mt-4">
            {{ $doctors->links() }}
        </div>
    </div>
</div>
@endsection