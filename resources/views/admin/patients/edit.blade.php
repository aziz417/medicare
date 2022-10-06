@extends('layouts.app')
@section('title', 'Edit Patient')

@section('content')
<header class="page-header">
    <h1 class="page-title">Edit account</h1>
</header>
<div class="page-content">
    <form class="mb-4 row" action="{{ route('admin.patients.update', $patient->id) }}" method="POST" enctype="multipart/form-data">
        <div class="col-12">
            <h5>Basic Info</h5>
            <label>Photo</label>
            <div class="form-group avatar-box d-flex align-items-center file-input">
                <img src="{{ asset($patient->avatar()) }}" width="100" height="100" alt="User Image" class="rounded-500 mr-4 img-placeholder">
                <label class="btn btn-outline-primary" type="button" for="avatar">
                    Change photo<span class="btn-icon icofont-ui-user ml-2"></span>
                    <input id="avatar" type="file" accept="image/*" name="avatar" class="hidden-input">
                </label>
                @error('avatar')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
        </div>
        <div class="col col-12 col-md-6">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Full name</label>
                <input class="form-control" type="text" placeholder="Full name" name="name" value="{{ $patient->name }}">
                @error('name')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input class="form-control" type="email" placeholder="Email" name="email" value="{{ $patient->email }}">
                @error('email')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Phone number</label>
                <input class="form-control" type="text" minlength="11" maxlength="13" placeholder="Mobile number" name="mobile" value="{{ $patient->mobile ?? old('mobile') }}">
                @error('mobile')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" placeholder="Address" rows="2" name="address">{{ $patient->getMeta('user_address', old('address')) }}</textarea>
                @error('address')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </div>
        </div>
        <div class="col col-12 col-md-6">
            <h5>Others Details</h5>
            <div class="row">
                <div class="form-group col-6">
                    <label>Blood Group</label>
                    <input class="form-control" type="text" placeholder="Blood Group" name="blood_group" value="{{ $patient->getMeta('user_blood_group', old('blood_group')) }}">
                    @error('meta_blood_group')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group col-6">
                    <label>Age</label>
                    <input class="form-control" type="number" placeholder="Age" name="age" value="{{ $patient->getMeta('user_age', old('age')) }}">
                    @error('meta_age')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group col-6">
                    <label>Gender</label>
                    <select class="selectpicker" title="Gender" name="gender">
                        <option value="">Select Gender</option>
                        <option {{ $patient->getMeta('user_gender', old('gender'))=='male' ? 'selected':'' }} value="male">Male</option>
                        <option {{ $patient->getMeta('user_gender', old('gender'))=='female' ? 'selected':'' }} value="female">Female</option>
                        <option {{ $patient->getMeta('user_gender', old('gender'))=='other' ? 'selected':'' }} value="other">Other</option>
                    </select>
                    @error('meta_gender')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="selectpicker">
                    <option {{ $patient->status == 'active' ? 'selected':'' }} value="active">Active</option>
                    <option {{ $patient->status == 'blocked' ? 'selected':'' }} value="blocked">Blocked</option>
                    <option {{ $patient->status == 'disabled' ? 'selected':'' }} value="disabled">Disabled</option>
                </select>
            </div>


        </div>
    </form>
</div>
@endsection