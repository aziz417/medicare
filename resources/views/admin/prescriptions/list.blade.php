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
                            <th scope="col">Patient</th>
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
                                <a class="table-user d-flex align-items-center" href="{{ route('admin.patients.show', $prescription->patient_id) }}">
                                    <img src="{{ asset($prescription->patient->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                    <div  class="ml-2">
                                        <strong>{{ $prescription->patient->name ?? 'N/A' }}</strong><br>
                                        <small><span class="icon-responsive icofont-ui-cell-phone p-0"></span>
                                        {{ $prescription->patient->mobile ?? 'N/A' }}</small>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="text-muted text-nowrap">
                                    <a href="{{ route('admin.appointments.show', $prescription->appointment_id) }}">{{ $prescription->appointment->appointment_code ?? 'N/A' }}</a>
                                </div>
                            </td>
                            <td><a href="{{ route('admin.doctors.show', $prescription->doctor_id) }}">{{ $prescription->doctor->name ?? 'N/A' }}</a></td>
                            <td><div class="text-muted text-ellipsis" style="max-width: 150px;">{{ $prescription->chief_complain }}</div></td>
                            <td>
                                <span class="badge badge-{{statusClass($prescription->status)}}">{{ ucfirst($prescription->status) }}</span>
                            </td>
                            <td>
                                <form class="actions" action="{{ route('admin.prescriptions.destroy', $prescription->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method("DELETE")
                                    <a href="{{ route('admin.prescriptions.show', $prescription->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-eye"></span>
                                    </a>
                                    <a href="{{ route('admin.prescriptions.edit', $prescription->id) }}" class="btn btn-info btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-edit"></span>
                                    </a>
                                    @can('delete-prescription', $prescription)
                                    <button type="submit" class="btn btn-error btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-delete"></span>
                                    </button>
                                    @endcan
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">No Prescriptions</td>
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
    <div class="add-action-box">
        <a href="{{ route('admin.prescriptions.create') }}" class="btn btn-primary btn-lg btn-square rounded-pill">
            <span class="btn-icon icofont-presentation-alt"></span>
        </a>
    </div>
</div>
@endsection