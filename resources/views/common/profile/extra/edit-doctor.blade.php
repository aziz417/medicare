<div class="row">
    <div class="col-sm-4">
        <div class="form-group">
            <label>Gender</label>
            <select class="selectpicker" title="Gender" name="meta_gender">
                <option value="">Select Gender</option>
                <option {{ $auth->getMeta('user_gender')=='Male' ? 'selected':'' }} value="Male" selected>Male</option>
                <option {{ $auth->getMeta('user_gender')=='Female' ? 'selected':'' }} value="Female">Female</option>
                <option {{ $auth->getMeta('user_gender')=='Other' ? 'selected':'' }} value="Other">Other</option>
            </select>
            @error('meta_gender')
            <span class="invalid-feedback" role="alert">{{ $message }}
            @enderror
        </div>
    </div>
    <div class="col-sm-4 form-group">
        <label>Signature</label>
        <input class="form-control" type="file" accept="image/*" name="signature" value="{{ $auth->getMeta('user_signature') ?? old('signature') }}">
        <span class="text-muted">Will be show in prescription.</span>
        @error('signature')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
    <div class="col-sm-4">
        @if( $auth->getMeta('user_signature') )
        <img style="max-height: 80px;" src="{{ asset($auth->getMeta('user_signature')) }}" alt="Signature">
        @endif
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-6">
        <label>Designation</label>
        <input class="form-control" type="text" placeholder="Designation" name="meta_designation" value="{{ $auth->getMeta('user_designation') ?? old('meta_designation') }}">
        @error('meta_designation')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
    <div class="form-group col-sm-6">
        <label>Department</label>
        <select name="user_department_id" id="department" class="selectpicker" required>
            @foreach($departments ?? [] as $item)
                <option {{ $auth->department_id ?? old('user_department_id')==$item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>
        @error('user_department_id')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
    <div class="col-sm-6 form-group">
        <label>Education Title</label>
        <input class="form-control" type="text" placeholder="Education title" name="meta_education_title" value="{{ $auth->getMeta('user_education_title') ?? old('meta_education_title') }}">
        <span class="text-muted">Will be show in prescription and profile.</span>
        @error('meta_education_title')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
    <div class="form-group col-sm-6">
        <label>Specialization</label>
        <input class="form-control" type="text" placeholder="Specialization; Seperated by comma" name="meta_specialization" value="{{ $auth->getMeta('user_specialization') ?? old('meta_specialization') }}">
        @error('meta_specialization')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
</div>
<div class="form-group ">
    <label>About Yourself</label>
    <textarea name="meta_about" placeholder="Write somethings..." id="about" cols="30" rows="4" class="form-control">{{ $auth->getMeta('user_about', old('meta_about')) }}</textarea>
    @error('meta_about')
    <span class="invalid-feedback" role="alert">{{ $message }}
    @enderror
</div>
<h5>Appointment Charges</h5>
<div class="row">
    <div class="form-group col-4">
        <label>Booking</label>
        <input class="form-control" type="number" placeholder="New Appointment" name="charge_booking" value="{{ $auth->getCharge('booking')->amount }}" required>
        @error('charge_booking')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
    <div class="form-group col-4">
        <label>Re-Appointment</label>
        <input class="form-control" type="number" placeholder="Re-Appointment" name="charge_reappoint" value="{{ $auth->getCharge('reappoint')->amount }}" required>
        @error('charge_reappoint')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
    <div class="form-group col-4">
        <label>Report Showing</label>
        <input class="form-control" type="number" placeholder="Report show" name="charge_report" value="{{ $auth->getCharge('report')->amount }}" required>
        @error('charge_report')
        <span class="invalid-feedback" role="alert">{{ $message }}
        @enderror
    </div>
</div>

