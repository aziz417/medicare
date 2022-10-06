@extends('layouts.app')
@section('title', 'Edit User')

@section('content')
<header class="page-header">
    <h1 class="page-title">Edit User</h1>
</header>
<div class="page-content">
    <form class="mb-4 row justify-content-center" action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        <div class="col col-12 col-md-6">
            <h5>Basic Info</h5>
            <label>Photo</label>
            <div class="form-group avatar-box d-flex align-items-center file-input">
                <img src="{{ asset($user->avatar()) }}" width="100" height="100" alt="User Image" class="rounded-500 mr-4 img-placeholder">
                <label class="btn btn-outline-primary" type="button" for="avatar">
                    Change photo<span class="btn-icon icofont-ui-user ml-2"></span>
                    <input id="avatar" type="file" accept="image/*" name="avatar" class="hidden-input">
                </label>
                @error('avatar')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            @csrf @method('PUT')
            <div class="form-group">
                <label>Full name</label>
                <input class="form-control" type="text" placeholder="Full name" name="name" value="{{ $user->name }}">
                @error('name')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input class="form-control" type="email" placeholder="Email" name="email" value="{{ $user->email }}">
                @error('email')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label>Phone number</label>
                <input class="form-control" type="text" minlength="11" maxlength="13" placeholder="Mobile number" name="mobile" value="{{ $user->mobile ?? old('mobile') }}">
                @error('mobile')
                <span class="invalid-feedback" role="alert">{{ $message }}
                @enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="selectpicker">
                    <option {{ $user->status == 'active' ? 'selected':'' }} value="active">Active</option>
                    <option {{ $user->status == 'blocked' ? 'selected':'' }} value="blocked">Blocked</option>
                    <option {{ $user->status == 'disabled' ? 'selected':'' }} value="disabled">Disabled</option>
                </select>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select name="role" id="role" class="selectpicker" title="Role">
                    @foreach(config('system.user_roles') as $role)
                    @if( $role['key'] != 'master' && !$auth->isSuperAdmin() )
                    <option {{ $user->role==$role['key'] ? 'selected':'' }} value="{{ $role['key'] }}">{{ $role['name'] }}</option>
                    @endif
                    @endforeach
                </select>
                @error('role')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-success">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection