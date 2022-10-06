@extends('layouts.app')
@section('title', "Appointment ".ucfirst($action))

@push('header')
<style type="text/css">
    #chat-box .message-list {
        height: calc(100vh - 230px)
    }
</style>
@endpush

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-7 col-sm-12">
            @if( $action == 'chat' && $appointment )
            <x-chat-box type="page" :room="$appointment" />
            @endif
        </div>
        <div class="col-md-5 col-sm-12 d-none d-md-block">
            <h5 class="mt-0">Appointment Summery</h5>
            <table class="table list-table">
                <tbody>
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
                        <td>Appointment Time</td>
                        <td>:</td>
                        <td>{{ $appointment->scheduled_at->format('d M, Y - h:i A') }}</td>
                    </tr>
                    <tr>
                        <td>Problem</td>
                        <td>:</td>
                        <td>{{ $appointment->patient_problem }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection