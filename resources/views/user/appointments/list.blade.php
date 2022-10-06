@extends('layouts.app')
@section('title', 'Appointments')
@section('content')
<header class="page-header">
    <h1 class="page-title">Appointments</h1>
    <a href="{{ route('user.appointments.create') }}" class="btn btn-outline-primary h-100"><span class="btn-icon icofont-stethoscope-alt mr-2"></span> Book Appointment</a>
</header>
<div class="page-content">
    <div class="card mb-0">
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
                                <div class="text-muted text-nowrap" title="{{ $appointment->isExpired('message') }}" data-toggle="tooltip">
                                    <strong class="{{ $appointment->isExpired('class') }}">{{ $appointment->appointment_code }}</strong> 
                                </div>
                            </td>
                            <td>
                                <a class="table-user d-flex align-items-center" href="{{ route('user.doctors.show', $appointment->doctor_id) }}">
                                    <img src="{{ asset($appointment->doctor->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                    <div  class="ml-2">
                                        <strong>{{ $appointment->doctor->name }}</strong><br>
                                        <small><span class="icon-responsive icofont-ui-cell-phone p-0"></span>
                                        {{ $appointment->doctor->email }}</small>
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
                                <form class="actions" action="{{ route('user.appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method("DELETE")
                                    <a href="{{ route('user.appointments.show', $appointment->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-eye"></span>
                                    </a>
                                    @if( ! $appointment->isConfirmed() && !$appointment->isCanceled() )
                                    <a href="#edit-appointment" data-id="{{ $appointment->id }}" data-link="{{ route('user.appointments.update', $appointment->id) }}" class="edit-appointment btn btn-info btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-edit"></span>
                                    </a>
                                    <a href="{{ route('payment.appointment', [
                                    'appointment' => $appointment->id, 
                                    'gateway' => $appointment->lastTransaction->gateway ?? 'manual',
                                    'method' => $appointment->lastTransaction->method ?? '',
                                    ]) }}" class="btn btn-{{ empty($appointment->transaction_id) ? 'danger' : 'warning' }} btn-sm btn-square rounded-pill" title="Make Payment" data-toggle="tooltip">
                                        <span class="btn-icon icofont-money-bag"></span>
                                    </a>
                                    @endif
                                    @if( $appointment->isCanceled() )
                                    <button type="submit" class="btn btn-error btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-delete"></span>
                                    </button>
                                    @endif
                                    </form>
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
            <div class="mt-4">
                {{ $appointments->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="edit-appointment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="javascript:void(0)" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit appointment</h5>
            </div>
            <div class="modal-body">
                @csrf @method('PUT')
                <h6>You can update only your problem summery!</h6>
                <div class="form-group" required>
                    <textarea class="form-control" id="aedit-pproblem" name="patient_problem" cols="30" rows="2" placeholder="Patient Problem">{{ old('patient_problem') }}</textarea>
                    @error('patient_problem')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="actions justify-content-between">
                    <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Update appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endpush


@push('footer')
<script type="text/javascript">
    const APPOINTMENTS = @json($appointments);
    (function($){
        $('.edit-appointment').on('click', function(e){
            e.preventDefault();
            var $this = $(this), ID = $this.data('id'), link = $this.data('link'),
                APPOINTMENT = APPOINTMENTS.data.find(item=>item.id===Number(ID));
            if( APPOINTMENT ){
                $modal = $('#edit-appointment');
                $modal.find('form').attr('action', link);
                $modal.find('#aedit-pproblem').val(APPOINTMENT.patient_problem);
                $modal.modal('show');
            }
        })
    })(jQuery);
</script>
@endpush