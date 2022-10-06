@extends('layouts.app')
@section('title', 'Dashboard')

@push('header')
<link rel="stylesheet" href="{{ asset('assets/css/Chart.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/morris.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/leaflet.css') }}">
@endpush

{{-- <div class="toast" style="position: absolute; top: 50px; right: 12px; margin: 10px; opacity: 1;">
    <div class="toast-header">
        <strong class="mr-auto">Bootstrap</strong>
        <small>11 mins ago</small>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        Hello, world! This is a toast message.
    </div>
</div> --}}

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col col-12 col-md-6 col-xl-3">
            <div class="card animated fadeInUp delay-01s bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-5">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-first-aid-alt"></div>
                        </div>
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Appointments</h6>
                            <div class="count text-primary fs-20">{{ sprintf('%02s', $dashboard['appointments'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 col-xl-3">
            <div class="card animated fadeInUp delay-02s bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-5">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-wheelchair"></div>
                        </div>
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Patients</h6>
                            <div class="count text-primary fs-20">{{ sprintf('%02s', $dashboard['patients'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 col-xl-3">
            <div class="card animated fadeInUp delay-03s bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-5">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-blood"></div>
                        </div>
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1">Prescriptions</h6>
                            <div class="count text-primary fs-20">{{ sprintf('%02s', $dashboard['prescriptions'] ?? 0) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6 col-xl-3">
            <div class="card animated fadeInUp delay-04s bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col col-5">
                            <div class="icon p-0 fs-48 text-primary opacity-50 icofont-dollar-true"></div>
                        </div>
                        <div class="col col-7">
                            <h6 class="mt-0 mb-1 text-nowrap">Total Earning</h6>
                            <div class="count text-primary fs-20">{{ inCurrency(sprintf('%02s', $dashboard['earning'] ?? 0)) }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-0">
        <div class="card-header">Recent Appointments</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Code</th>
                            @if( ! $auth->isRole('doctor') )
                            <th scope="col">Doctor</th>
                            @endif
                            <th scope="col">Patient</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Injury / Condition</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="text-muted text-nowrap" title="{{ $appointment->isExpired('message') }}" data-toggle="tooltip">
                                    <strong class="{{ $appointment->isExpired('class') }}">{{ $appointment->appointment_code }}</strong> 
                                </div>
                            </td>
                            @if( ! $auth->isRole('doctor') )
                            <td>
                                <a class="table-user d-flex align-items-center" href="{{ route('admin.doctors.show', $appointment->doctor_id) }}">
                                    <div class="img-circle">
                                        <img src="{{ asset(optional($appointment->doctor)->avatar() ?? 'assets/content/doctor.png') }}" alt="{{$appointment->doctor->name ?? ''}}" class="rounded-500">
                                    </div>
                                    <div  class="ml-2">
                                        <strong>{{ $appointment->doctor->name ?? 'N/A' }}</strong><br>
                                        <small><span class="icon-responsive icofont-ui-cell-phone p-0"></span>
                                        {{ $appointment->doctor->email ?? 'N/A' }}</small>
                                    </div>
                                </a>
                            </td>
                            @endif
                            <td>
                                <a class="table-user d-flex align-items-center" href="{{ route('admin.patients.show', $appointment->user_id) }}">
                                    <img src="{{ asset(optional($appointment->patient)->avatar() ?? 'assets/content/user.png') }}" alt="" width="40" height="40" class="rounded-500">
                                    <div  class="ml-2">
                                        <strong>{{ $appointment->patient->name ?? 'N/A' }}</strong><br>
                                        <small><span class="icon-responsive icofont-ui-cell-phone p-0"></span>
                                        {{ $appointment->patient->mobile ?? 'N/A' }}</small>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="text-muted text-nowrap">
                                    {{ optional($appointment->scheduled_at)->format('d M Y') }}<br>
                                    <strong>{{ optional($appointment->scheduled_at)->format('h:i A') }}</strong> 
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{statusClass($appointment->status)}}">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td><div class="text-muted text-ellipsis" style="max-width: 150px;">{{ $appointment->patient_problem }}</div></td>
                            <td>
                                <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill">
                                    <span class="btn-icon icofont-eye"></span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">No Appointment</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer')
<script src="{{ asset('assets/js/jquery.barrating.min.js') }}"></script>
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>
<script src="{{ asset('assets/js/raphael-min.js') }}"></script>
<script src="{{ asset('assets/js/morris.min.js') }}"></script>
<script src="{{ asset('assets/js/echarts.min.js') }}"></script>
<script src="{{ asset('assets/js/echarts-gl.min.js') }}"></script>
@endpush
