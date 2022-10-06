@extends('layouts.app')
@section('title', 'View Departments')

@section('content')
<header class="page-header">
    <div class="mb-3">
        <h1 class="mb-0 page-title">{{ $department->name }}</h1>
        <p>{{ $department->description }}</p>
    </div>
</header>
<div class="page-content">
    <div class="card mb-0">
        <div class="card-header">Doctors in <strong>{{ $department->name }}</strong> Department</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Doctor</th>
                            <th scope="col">Designation</th>
                            <th scope="col">Email</th>
                            <th scope="col">Mobile</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($department->doctors as $doctor)
                            <tr>
                                <td>
                                    <img src="{{ asset($doctor->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                    <strong>{{ $doctor->name }}</strong>
                                </td>
                                <td>
                                    <div class="text-muted text-nowrap">{{ $doctor->getMeta('user_designation', '~') }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center nowrap text-primary"><span class="icofont-ui-email p-0 mr-2"></span> {{ $doctor->email }}</div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center nowrap text-primary"><span class="icofont-ui-cell-phone p-0 mr-2"></span> {{ $doctor->mobile }}</div>
                                </td>
                                <td>
                                    <div class="text-muted text-nowrap"><div class="badge badge-sm badge-info-outine">{{ ucfirst($doctor->status ?? 'active') }}</div></div>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-info btn-sm btn-square rounded-pill"><span class="btn-icon icofont-eye-alt"></span></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        <tr>
                            <td colspan="7">No Doctor found in this department!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection