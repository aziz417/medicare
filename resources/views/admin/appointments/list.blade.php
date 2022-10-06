@extends('layouts.app')
@section('title', 'Appointments')
@section('content')
<header class="page-header">
    <h1 class="page-title">Appointments</h1>
</header>
<div class="page-content">
    <div class="card mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">Patient</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col">Doctor</th>
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
                                <a class="table-user d-flex align-items-center" href="{{ route('admin.patients.show', $appointment->user_id) }}">
                                    <img src="{{ asset($appointment->patient->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                    <div  class="ml-2">
                                        <strong>{{ $appointment->patient->name ?? 'N/A' }}</strong><br>
                                        <small><span class="icon-responsive icofont-ui-cell-phone p-0"></span>
                                        {{ $appointment->patient->mobile ?? 'N/A' }}</small>
                                    </div>
                                </a>
                            </td>
                            <td>
                                <div class="text-muted text-nowrap">
                                    {{ optional($appointment->scheduled_at)->format('d M Y') }} <br>
                                    <strong>{{ optional($appointment->scheduled_at)->format('h:i A') }}</strong> 
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.doctors.show', $appointment->doctor_id) }}">{{ $appointment->doctor->name ?? 'N/A' }}</a><br>
                                <a class="text-dark" href="{{ route('admin.departments.show', $appointment->doctor->department_id ?? 'N/A') }}">{{ $appointment->doctor->department->name ?? '' }}</a>
                            </td>
                            <td>
                                <span data-toggle="tooltip" title="Appointment: {{ $appointment->is_completed ? 'Complete' :'Incomplete' }}" class="badge badge-{{statusClass($appointment->status)}}">{{ ucfirst($appointment->status) }}</span>
                            </td>
                            <td><div class="text-muted text-ellipsis" style="max-width: 150px;">{{ $appointment->patient_problem }}</div></td>
                            <td>
                                <form class="actions" action="{{ route('admin.appointments.destroy', $appointment->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method("DELETE")
                                    <a href="{{ route('admin.appointments.show', $appointment->id) }}" class="btn btn-primary btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-eye"></span>
                                    </a>
                                    <a data-toggle="tooltip" title="Click to create prescription" href="{{ route('admin.prescriptions.create', ['appointment'=>$appointment->id]) }}" class="btn btn-default btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-file-alt"></span>
                                    </a>
                                    @if( $auth->can('edit-appointment', $appointment) )
                                    <a href="javascript:void(0)" data-id="{{ $appointment->id }}" data-link="{{ route('admin.appointments.update', $appointment->id) }}" class="edit-appointment btn btn-info btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-edit"></span>
                                    </a>
                                    {{-- @elseif( $appointment->isConfirmed() && $auth->isRole('doctor') )
                                    <a title="Start video chat" data-toggle="tooltip" href="{{ route('admin.appointments.action', [$appointment->id, 'video']) }}" class="edit-appointment btn btn-info btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-video-chat"></span>
                                    </a> --}}
                                    @endif
                                    @if( $auth->can('delete-appointment', $appointment) )
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
    <div class="add-action-box">
        <button class="btn btn-primary btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-appointment">
            <span class="btn-icon icofont-stethoscope-alt"></span>
        </button>
    </div>
</div>
@endsection

@push('footer')
<div class="modal fade" id="add-appointment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('admin.appointments.store') }}" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new appointment</h5>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <select id="appointment-type" name="type" class="selectpicker" title="Appointment type" placeholder="Type">
                        <option {{ old('type')=='booking' ?'selected' :'' }} value="booking">Booking</option>
                        <option {{ old('type')=='reappoint' ?'selected' :'' }} value="reappoint">Re Appointment</option>
                        <option {{ old('type')=='report' ?'selected' :'' }} value="report">Report Showing</option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="user_id" id="patient" class="selectpicker" title="Patient" required>
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                        <option {{ old('user_id')==$patient->id ?'selected' :'' }} value="{{ $patient->id }}">{{ $patient->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <select name="doctor_id" id="appointment-doctor" class="selectpicker" title="Patient" required>
                        <option value="">Select Doctor</option>
                        @foreach($doctors as $doctor)
                        <option {{ old('doctor_id', $auth->id)==$doctor->id ?'selected' :'' }} value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                    @error('doctor_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input class="form-control" name="scheduled_date" id="appointment-date" type="date" min="{{ date('Y-m-d') }}" placeholder="Date" value="{{ old('scheduled_date') }}" required>
                    @error('scheduled_date')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input type="hidden" id="appointment-schdule-id" value="{{ old('schedule_id') }}" name="schedule_id">
                    <select name="scheduled_time" class="form-control" id="appointment-slot" required>
                        @if( old('scheduled_time') )
                        <option value="{{ old('scheduled_time') }}">{{ _date(old('scheduled_date').' '.old('scheduled_time'), 'l h:i A') }}</option>
                        @endif
                        <option value="">Select Slot</option>
                    </select>
                    @error('scheduled_time')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                    @error('schedule_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <input class="form-control" type="number" name="appointment_fee" placeholder="Appointment Charge" value="{{ old('appointment_fee') }}" required>
                            @error('appointment_fee')
                            <span class="invalid-feedback" role="alert">{{ $message }}
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <input class="form-control" type="number" value="{{ old('discount') }}" name="discount" placeholder="Discount">
                            @error('discount')
                            <span class="invalid-feedback" role="alert">{{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="form-group" required>
                    <textarea class="form-control" name="patient_problem" cols="30" rows="2" placeholder="Patient Problem">{{ old('patient_problem') }}</textarea>
                    @error('patient_problem')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <select name="status" class="selectpicker">
                        <option {{ old('status')=='pending' ? 'selected' :'' }} value="pending">Pending</option>
                        <option {{ old('status')=='blocked' ? 'selected' :'' }} value="blocked">Waiting For Payment</option>
                        <option {{ old('status')=='confirmed' ? 'selected' :'' }} value="confirmed">Confirmed</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="actions justify-content-between">
                    <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add appointment</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="edit-appointment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="javascript:void(0)" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit appointment</h5>
            </div>
            <div class="modal-body">
                <div id="info">
                    <div class="form-group">
                        <input type="text" class="form-control text-capitalize" id="aedit-type" readonly>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="aedit-doctor" readonly>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="aedit-patient" readonly>
                    </div>
                </div>
                @csrf @method('PUT')
                <div class="form-group">
                    <input type="hidden" id="aedit-schedule" name="schedule_id">
                    <input class="form-control" name="scheduled_date" id="aedit-date" type="date" placeholder="Date" value="{{ old('scheduled_date') }}" required>
                    @error('scheduled_date')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <select name="scheduled_time" class="form-control" id="aedit-slot" required>
                        <option value="">Select Slot</option>
                    </select>
                    @error('scheduled_time')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                    @error('schedule_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <input class="form-control" id="aedit-fee" type="number" name="appointment_fee" placeholder="Appointment Charge" value="{{ old('appointment_fee') }}" required>
                    @error('appointment_fee')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                
                <div class="form-group" required>
                    <textarea class="form-control" id="aedit-pproblem" name="patient_problem" cols="30" rows="2" placeholder="Patient Problem">{{ old('patient_problem') }}</textarea>
                    @error('patient_problem')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <select name="status" class="selectpicker" id="aedit-status">
                        <option {{ old('status')=='pending' ? 'selected' :'' }} value="pending">Pending</option>
                        <option {{ old('status')=='waiting' ? 'selected' :'' }} value="blocked">Waiting For Payment</option>
                        <option {{ old('status')=='confirmed' ? 'selected' :'' }} value="confirmed">Confirmed</option>
                    </select>
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
    const DOCTORS = @json($doctors);
    const APPOINTMENTS = @json($appointments);
    (function($){
        function updateSlotField(ID, list, selectedDate, update = false){
            var DOCTOR = DOCTORS.find(item=>item.id===Number(ID));
            if( DOCTOR ){
                var datetime = moment(selectedDate), day = datetime.format('dddd'),
                    slots = DOCTOR.slots.filter(item=>item.day===day), options = [];
                    console.log(selectedDate);
                if( slots.length > 0 ){
                    slots.forEach(item=>{
                        options.push(`<option${datetime.format('HH:mm')===item.time ?' selected':''} data-schedule="${item.schedule_id}" value="${item.time}">${item.day} ${moment(item.time, 'HH:mm').format('hh:mm A')}</option>`);
                    });
                }else{
                    showToaster("Slots not found, Try again in another day.", {title: "Booking Error"})
                    options.push(`<option value="">No Slots Found</option>`);
                }
                $slotsList = list;
                $slotsList.html(options.join(''));
                if( $slotsList.parent('.bootstrap-select').length > 0 ){
                    $slotsList.selectpicker('refresh')
                }else{
                    $slotsList.selectpicker({
                        style: '',
                        styleBase: 'form-control',
                        tickIcon: 'icofont-check-alt'
                    });
                }
            }
        }
        $('#appointment-date').on('change', function(){
            var $this = $(this);
            if($('#appointment-doctor').val() === ""){
                alert('Select Doctor First');
                $('#appointment-doctor').focus();
            }else{
                var ID = $('#appointment-doctor').val();
                updateSlotField(ID, $('#appointment-slot'), $this.val());
            }
        });
        $('#appointment-doctor').on('change', function(){
            var ID = $(this).val(),
                DOCTOR = DOCTORS.find(item=>item.id===Number(ID)),
                Atype = $('#appointment-type').val(),
                charge = DOCTOR.charges.find(item=>item.type===Atype);
            if( DOCTOR ){
                $('input[name="appointment_fee"]').val(charge.amount);
            }
        })
        $('#appointment-slot').on('change', function(){
            var $this = $(this), value = $this.val();
            if( value !=="" ){
                var schedule_id = $('#appointment-slot').find(`option[value="${value}"]`).data('schedule');
                if( schedule_id ){
                    $('#appointment-schdule-id').val(schedule_id);
                }
            }
        })
        $('#appointment-slot').on('click', function(){
            if( $('#appointment-doctor').val() === "" ){
                alert('Select Doctor First');
                $('#appointment-doctor').focus();
            }
            if( $('#appointment-doctor').val() !== "" && $('#appointment-date').val() === "" ){
                alert('Select Date First');
                $('#appointment-date').focus();
            }
        });
        $('.edit-appointment').on('click', function(){
            var $this = $(this), ID = $this.data('id'), link = $this.data('link'),
                APPOINTMENT = APPOINTMENTS.data.find(item=>item.id===Number(ID));
            if( APPOINTMENT ){
                $modal = $('#edit-appointment');
                $modal.find('form').attr('action', link);
                $modal.find('#aedit-type').val(APPOINTMENT.type);
                $modal.find('#aedit-schedule').val(APPOINTMENT.schedule_id);
                $modal.find('#aedit-doctor').val(APPOINTMENT.doctor.name);
                $modal.find('#aedit-patient').val(APPOINTMENT.patient.name);
                $modal.find('#aedit-date').val(moment(APPOINTMENT.scheduled_date).format('YYYY-MM-DD'));
                $modal.find('#aedit-fee').val(APPOINTMENT.appointment_fee);
                $modal.find('#aedit-pproblem').val(APPOINTMENT.patient_problem);
                updateSlotField(APPOINTMENT.doctor_id, $('#aedit-slot'), moment(APPOINTMENT.scheduled_at).format('YYYY-MM-DD HH:mm'), true);
                $modal.find('#aedit-slot').find(`option[value="${APPOINTMENT.scheduled_time}"]`).prop('selected', true);
                var appointment_status = (APPOINTMENT.status === 'confirmed' || APPOINTMENT.status === 'approved') ? 'confirmed' : APPOINTMENT.status;
                $modal.find('#aedit-status').find(`option[value="${appointment_status}"]`).prop('selected', true);
                $modal.find('#aedit-status').selectpicker('refresh');
                $modal.modal('show');
            }

        })
    })(jQuery)
</script>
@endpush