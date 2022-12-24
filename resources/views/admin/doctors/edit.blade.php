@extends('layouts.app')
@section('title', 'Edit Doctors')

@section('content')
<header class="page-header">
    <h1 class="page-title">Edit account</h1>
</header>
<div class="page-content">
    <form class="mb-4 row" action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data">
        <div class="col col-12 col-md-6">
            @csrf @method('PUT')
            <h5>Basic Info</h5>
            <label>Photo</label>
            <div class="form-group avatar-box d-flex align-items-center file-input">
                <img src="{{ asset($doctor->avatar()) }}" width="100" height="100" alt="User Image" class="rounded-500 mr-4 img-placeholder">
                <label class="btn btn-outline-primary" type="button" for="avatar">
                    Change photo<span class="btn-icon icofont-ui-user ml-2"></span>
                    <input id="avatar" type="file" accept="image/*" name="avatar" class="hidden-input">
                </label>
                @error('avatar')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Full name</label>
                <input class="form-control" type="text" placeholder="Full name" name="name" value="{{ $doctor->name }}">
                @error('name')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            @if($auth->is_desk_doctor != 1)
            <div class="form-group">
                <label>Email Address</label>
                <input class="form-control" type="email" placeholder="Email" name="email" value="{{ $doctor->email }}">
                @error('email')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label>Phone number</label>
                <input class="form-control" type="text" minlength="11" maxlength="13" placeholder="Mobile number" name="mobile" value="{{ $doctor->mobile ?? old('mobile') }}">
                @error('mobile')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            @endif
            <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" placeholder="Address" rows="2" name="address">{{ $doctor->getMeta('user_address', old('user_address')) }}</textarea>
                @error('address')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <h5>Professional Details</h5>
            <div class="row">
                <div class="form-group col-6">
                    <label>Designation</label>
                    <input class="form-control" type="text" placeholder="Designation" name="designation" value="{{ $doctor->getMeta('user_designation', old('designation')) }}">
                    @error('designation')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-12">
                    <label>Department</label>
                    <select name="department_id[]" multiple="multiple" id="department" class="selectpicker" required>
                        @forelse($departments as $department)
                            <option
                                    @if(isset($doctor))
                                    @foreach($doctor->departments as $doctorDepartment)
                                    {{ $doctorDepartment->id == $department->id ? 'selected' : '' }}
                                    @endforeach
                                    @endif value="{{ $department->id }}">
                                {{ $department->name }}
                            </option>
                        @endforeach
{{--                        @foreach($departments ?? [] as $item)--}}
{{--                            <option {{ $doctor->department_id ?? old('user_department_id')==$item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>--}}
{{--                        @endforeach--}}
                    </select>
                    @error('department_id')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group ">
                <label>About Details</label>
                <textarea name="about" placeholder="Write somethings..." id="about" cols="30" rows="4" class="form-control">{{ $doctor->getMeta('user_about', old('about')) }}</textarea>
                @error('about')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
            </div>
            <h5>Appointment Charges</h5>
            <div class="row">
                <div class="form-group col-4">
                    <label>Booking</label>
                    <input class="form-control" type="number" placeholder="New Appointment" name="charge_booking" value="{{ $doctor->getCharge('booking')->amount }}" required>
                    @error('charge_booking')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-4">
                    <label>Re-Appointment</label>
                    <input class="form-control" type="number" placeholder="Re-Appointment" name="charge_reappoint" value="{{ $doctor->getCharge('reappoint')->amount }}" required>
                    @error('charge_reappoint')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-4">
                    <label>Report Showing</label>
                    <input class="form-control" type="number" accept="00" placeholder="Report show" name="charge_report" value="{{ $doctor->getCharge('report')->amount }}" >
                    @error('charge_report')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="row">
                <div class="form-group col-4">
                    <label for="badges">Badges</label>
                    <select name="badges[]" id="badges" class="selectpicker" multiple required>
                        @foreach($badges ?? [] as $item)
                            <option {{ in_array($item->id, $doctor->badges->pluck('id')->toArray()) ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    @error('badges')
                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group col-4">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="selectpicker">
                        <option {{ $doctor->status == 'active' ? 'selected':'' }} value="active">Active</option>
                        <option {{ $doctor->status == 'blocked' ? 'selected':'' }} value="blocked">Blocked</option>
                        <option {{ $doctor->status == 'disabled' ? 'selected':'' }} value="disabled">Disabled</option>
                    </select>
                </div>
                <div class="form-group col-4">
                    <label for="status">Desk Doctor</label>
                    <select name="is_desk_doctor" id="is_desk_doctor" class="selectpicker">
                        <option {{ $doctor->is_desk_doctor == 1 ? 'selected':'' }} value="1">Yes</option>
                        <option {{ $doctor->is_desk_doctor == 0 ? 'selected':'' }} value="0">No</option>
                    </select>
                </div>
            </div>


        </div>
    </form>
</div>
@endsection
@push('script')
    <script>
        $(document).ready(function () {
            $('.department_id').select2();
        });
    </script>
@endpush
