@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="page-content">
    <div class="card mb-0">
        <div class="card-header">Upcoming appointments</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Injury / Condition</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="text-muted text-nowrap">
                                    <strong>{{ $appointment->appointment_code }}</strong> 
                                </div>
                            </td>
                            <td>
                                <a class="table-user d-flex align-items-center" href="{{ route('user.doctors.show', $appointment->doctor_id) }}">
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
                            <td>
                                <div class="text-muted text-nowrap">
                                    {{ $appointment->scheduled_at->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="text-muted text-nowrap">
                                    <strong>{{ $appointment->scheduled_at->format('h:i A') }}</strong> 
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-{{statusClass($appointment->status)}}">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td><div class="text-muted text-ellipsis" style="max-width: 150px;">{{ $appointment->patient_problem }}</div></td>
                            <td>
                                <a href="{{ route('user.appointments.show', $appointment->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill">
                                    <span class="btn-icon icofont-eye"></span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">No Upcoming Appointment, click <a href="{{ route('user.doctors.index') }}">here</a> to book an appointment!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- <x-chat-box appointment="12" /> --}}
@endsection
