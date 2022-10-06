@extends('layouts.app')
@section('title', 'Prescriptions')
@section('content')
<header class="page-header">
    <h1 class="page-title">Prescriptions</h1>
</header>
<div class="page-content">
    <div class="card mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Appointment</th>
                            <th scope="col">Doctor</th>
                            <th scope="col">Major Concern</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($prescriptions as $prescription)
                        <tr>
                            <td>
                                <div class="text-muted text-nowrap">
                                    <a href="{{ route('user.appointments.show', $prescription->appointment_id) }}">{{ $prescription->appointment->appointment_code ?? 'N/A' }}</a>
                                </div>
                            </td>
                            <td><a href="{{ route('user.doctors.show', $prescription->doctor_id) }}">{{ $prescription->doctor->name }}</a></td>
                            <td><div class="text-muted text-ellipsis" style="max-width: 150px;">{{ $prescription->chief_complain }}</div></td>
                            <td>
                                <span class="badge badge-{{statusClass($prescription->status)}}">{{ ucfirst($prescription->status) }}</span>
                            </td>
                            <td>
                                <a href="{{ route('user.prescriptions.show', $prescription->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill">
                                    <span class="btn-icon icofont-eye"></span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">No Prescription generate yet!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $prescriptions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection