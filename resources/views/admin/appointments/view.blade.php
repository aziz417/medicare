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
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card card-light">
                <div class="card-body">
                    <table class="table list-table">
                        <tbody>
                            <tr>
                                <td>Appointment</td>
                                <td>:</td>
                                <td>{{ $appointment->appointment_code }}</td>
                            </tr>
                            <tr>
                                <td>Is Completed?</td>
                                <td>:</td>
                                <td>{{ $appointment->is_completed ? 'Complete' : 'Incomplete' }}</td>
                            </tr>
                            <tr>
                                <td>Doctor</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <img src="{{ asset($appointment->doctor->avatar()) }}" width="50px" height="50px" alt="" class="rounded-50p">
                                        <div class="ml-2">
                                            <h4 class="m-0">
                                                <a href="{{ route('admin.doctors.show', $appointment->doctor_id) }}" >{{ $appointment->doctor->name }}</a></h4>
                                            <strong>{{ $appointment->doctor->department->name ?? '' }}</strong>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Patient</td>
                                <td>:</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-start">
                                        <img src="{{ asset($appointment->patient->avatar()) }}" width="50px" height="50px" alt="" class="rounded-50p">
                                        <div class="ml-2">
                                            <h4 class="m-0">
                                                <a href="{{ route('admin.patients.show', $appointment->user_id) }}" >{{ $appointment->patient->name }}</a></h4>
                                            <strong>{{ $appointment->patient->email ?? '' }}</strong>
                                        </div>
                                    </div>
                                </td>
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
                                    <a href="{{ $appointment->transaction_id ? route('admin.transactions.show', $appointment->transaction_id) : '' }}">{{ optional($appointment->lastTransaction)->tnx_id ?? 'N/A' }}</a>
                                    ( {{ ucfirst(optional($appointment->lastTransaction)->gateway ?? 'N/A') }} )
                                </td>
                            </tr>
                            @if( $appointment->comment )
                            <tr>
                                <td>Admin Comment</td>
                                <td>:</td>
                                <td>{{ $appointment->comment }}</td>
                            </tr>
                            @endif
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
        <div class="col-md-3">
            <div class="card card-light">
                <div class="card-header">Actions</div>
                <div class="card-body">
                    @if( $appointment->isConfirmed() )
                    <div class="btn-group btn-group-sm">
                        @can('start-appointment-call', $appointment)
                        @if( $appointment->timeIsApeared() || $auth->id == $appointment->doctor_id )
                        <button type="button" class="start-video-call btn btn-primary"><span class="btn-icon mr-2 icofont-ui-video-chat"></span> Start Video</button>
                        @endif
                        @endcan
                        <a href="{{ route('admin.appointments.action', [$appointment->id, 'chat']) }}" class="btn btn-success"><span class="btn-icon mr-2 icofont-ui-chat"></span> Start Chat</a>
                    </div>  
                    @endif
                    @if( !$appointment->isCompleted() )
                    <form class="mt-5" action="{{ route('admin.appointments.update.force', $appointment->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="is_completed" value="1">
                        <button class="btn btn-info" type="submit">Mark as Completed</button>
                    </form>
                    @endif
                    @if( $auth->isAdmin(false) && !$appointment->isConfirmed() )
                    <h5>Approve Manually</h5>
                    <form action="{{ route('admin.appointments.update.force', $appointment->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="form-group">
                            <label for="comment">Comment</label>
                            <input id="comment" class="form-control" type="text" name="comment" placeholder="Write something..." value="{{ old('comment') }}">
                        </div>

                        <div class="btn-group btn-block mt-2">
                            <button type="submit" name="action" value="success" class="btn btn-success">Approve</button>
                            <button type="submit" name="action" value="declined" class="btn btn-danger">Reject</button>
                        </div>
                    </form>
                    @endif
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