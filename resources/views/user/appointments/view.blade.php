@extends('layouts.app')
@section('title', 'View Appointment')

@push('container')
@if( $appointment )
<x-chat-box type="popup" :room="$appointment" />
@endif
@endpush

@section('content')
<header class="page-header d-flex justify-content-between">
    <h1 class="page-title">Appointment {{ $appointment->appointment_code }}</h1>
    <div class="appointment-info">
        <div title="{{ $appointment->getStatusMessage() }}" data-toggle="tooltip" class="badge rounded badge-{{ statusClass($appointment->status) }}">Status: &nbsp;&nbsp;<strong>{{ ucfirst($appointment->status) }}</strong></div>
    </div>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-8">
            <div class="card card-light">
                <div class="card-body">
                    <table class="table list-table">
                        <tbody>
                            @if( optional($appointment->patient)->isSubmember() )
                            <tr>
                                <td>Appointment For</td>
                                <td>:</td>
                                <td>
                                    <strong>{{ $appointment->patient->name }}</strong><br>
                                    <i>- {{ $appointment->patient->getMeta('relationship_with_member', 'Relation') }}</i>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>Appointment</td>
                                <td>:</td>
                                <td>{{ $appointment->appointment_code }}</td>
                            </tr>
                            <tr>
                                <td>Appointment Type</td>
                                <td>:</td>
                                <td class="text-capitalize">{{ $appointment->type }}</td>
                            </tr>
                            <tr>
                                <td>Appointment Date</td>
                                <td>:</td>
                                <td>{{ $appointment->scheduled_date->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td>Appointment Time</td>
                                <td>:</td>
                                <td>{{ $appointment->scheduled_at->format('h:i A') }}</td>
                            </tr>
                            <tr>
                                <td>Charge Amount</td>
                                <td>:</td>
                                <td>{{ inCurrency($appointment->appointment_fee) }}</td>
                            </tr>
                            <tr>
                                <td>Coupon Code</td>
                                <td>:</td>
                                <td>{{ $appointment->coupon_code ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Payable Amount</td>
                                <td>:</td>
                                <td>{{ $appointment->getPayableAmount(true) }}</td>
                            </tr>
                            <tr>
                                <td>Last Transaction</td>
                                <td>:</td>
                                <td>
                                    <a href="{{ $appointment->transaction_id ? route('user.transactions.show', $appointment->transaction_id) : '' }}">{{ optional($appointment->lastTransaction)->tnx_id ?? 'N/A' }}</a>
                                    ( {{ ucfirst(optional($appointment->lastTransaction)->gateway ?? 'N/A') }} )
                                    @if( !$appointment->isConfirmed() )
                                    | <a href="{{ route('payment.appointment', [
                                    'appointment' => $appointment->id, 
                                    'gateway' => $appointment->lastTransaction->gateway ?? 'manual',
                                    'method' => $appointment->lastTransaction->method ?? '',
                                    ]) }}">Process to Pay</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Problem Summery</td>
                                <td>:</td>
                                <td>{{ $appointment->patient_problem }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header">
                    Doctor Info
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-3 user-actions">
                        <img src="{{ asset($appointment->doctor->avatar()) }}" width="100" height="100" alt="" class="rounded-50p mr-4">
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 mt-0 mr-1">
                            <a href="{{ route('user.doctors.show', $appointment->doctor_id) }}" >{{ $appointment->doctor->name }}</a></h5>
                    </div>
                    <p class="text-muted">{{ $appointment->doctor->getMeta('user_designation') }} <br>
                        <strong>{{ $appointment->doctor->department->name }}</strong>
                    </p>
                    <div class="btn-group mt-2 w-100">
                        @if( $appointment->isConfirmed() )
                        @can('start-appointment-call', $appointment)
                        @if( $appointment->timeIsApeared())
                        <button type="button" class="start-video-call btn btn-primary"><span class="btn-icon mr-2 icofont-ui-video-chat"></span> Start Video</button>
                        @endif
                        @endcan
                        <a href="{{ route('user.appointments.action', [$appointment->id, 'chat']) }}" class="btn btn-success"><span class="btn-icon mr-2 icofont-ui-chat"></span> Start Chat</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer')
<script type="text/javascript">
    (function($){
        $('.start-video-call').on('click', function(){
            let __URL = "{{ route('common.video.call', $appointment->id) }}";
            window.open(__URL, "", "width=960,height=590,left=100,top=80");
        })
    })(jQuery)
</script>
@endpush