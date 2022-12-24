<div class="row">
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <label>Blood Group</label>
            <input class="form-control" type="text" placeholder="Blood Group" name="meta_blood_group" value="{{ $auth->getMeta('user_blood_group') ?? old('user_blood_group') }}">
            @error('meta_blood_group')
            <span class="invalid-feedback" role="alert">{{ $message }}
            @enderror
        </div>
    </div>
    <div class="col-12 col-sm-4">
        <div class="form-group">
            <label>Age</label>
            <input class="form-control" type="number" placeholder="Age" name="meta_age" value="{{ $auth->getMeta('user_age') ?? old('meta_age') }}">
            @error('meta_age')
            <span class="invalid-feedback" role="alert">{{ $message }}
            @enderror
        </div>
    </div>
    <div class="col-12 col-sm-4">
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
</div>
