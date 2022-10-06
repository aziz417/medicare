@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<header class="page-header">
    <h1 class="page-title">Edit account</h1>
</header>
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col col-12 col-xl-8">
            <form class="mb-4" action="{{ route('common.profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label>Photo</label>
                <div class="form-group avatar-box d-flex align-items-center file-input">
                    <img src="{{ asset($auth->avatar()) }}" width="100" height="100" alt="User Image" class="rounded-500 mr-4 img-placeholder">
                    <label class="btn btn-outline-primary" type="button" for="avatar">
                        Change photo<span class="btn-icon icofont-ui-user ml-2"></span>
                        <input id="avatar" type="file" accept="image/*" name="avatar" class="hidden-input">
                    </label>
                    @error('avatar')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label>Full name</label>
                    <input class="form-control" type="text" placeholder="Full name" name="user_name" value="{{ $auth->name }}">
                    @error('user_name')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input class="form-control" type="email" placeholder="Email" name="user_email" value="{{ $auth->email }}">
                    @error('user_email')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label>Phone number</label>
                    <input class="form-control" type="text" minlength="11" maxlength="13" placeholder="Mobile number" name="user_mobile" value="{{ $auth->mobile ?? old('user_mobile') }}">
                    @error('user_mobile')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea class="form-control" placeholder="Address" rows="2" name="meta_address">{{ $auth->getMeta('user_address', old('user_address')) }}</textarea>
                    @error('meta_address')
                    <span class="invalid-feedback" role="alert">{{ $message }}
                    @enderror
                </div>
                @includeIf("common.profile.extra.edit-{$auth->role}")
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-success">Save changes</button>
                    </div>
                </div>
            </form>
            <hr>
            <h4>Change password</h4>
            <form action="{{ route('common.profile.password') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label>Current password</label>
                            <input class="form-control" type="password" name="old_password" placeholder="Current password">
                            @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label>New password</label>
                            <input class="form-control" type="password" name="password" placeholder="New password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="form-group">
                            <label>Confirm new password</label>
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-outline-dark">Change password</button>
            </form>
        </div>
    </div>
</div>
@endsection
