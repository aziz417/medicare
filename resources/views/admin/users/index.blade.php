@extends('layouts.app')
@section('title', 'Users')

@section('content')
<header class="page-header">
    <h1 class="page-title">Users</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-info text-white">
                                    <th scope="col">User</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td width="160px" class="d-flex">
                                            <img src="{{ asset($user->avatar()) }}" alt="" width="40" height="40" class="rounded-500">
                                            <div class="ml-2 info-box">
                                                <strong>{{ $user->name }}</strong> <br>
                                                <div class="badge badge-sm badge-{{ statusClass($user->role) }}">{{ ucfirst($user->role) }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center nowrap text-primary"><span class="icofont-ui-email p-0 mr-2"></span> {{ $user->email }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center nowrap text-primary"><span class="icofont-ui-cell-phone p-0 mr-2"></span> {{ $user->mobile }}</div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap"><div class="badge badge-{{ $user->status=='active' ? 'success': 'warning' }}">{{ ucfirst($user->status ?? 'active') }}</div></div>
                                        </td>
                                        <td>
                                            <form class="actions" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure?')" method="POST">
                                                @csrf @method('DELETE')
                                                @if( $auth->isSuperAdmin() || !$user->isSuperAdmin() )
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-info btn-sm btn-square rounded-pill"><span class="btn-icon icofont-ui-edit"></span></a>
                                                @endif
                                                @if( $auth->isAdmin(false) && $auth->id != $user->id && !$user->isRole('master') )
                                                <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit"><span class="btn-icon icofont-trash"></span></button>
                                                @endif
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="7">No User Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-action-box">
        <button class="btn btn-dark btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-user">
            <span class="btn-icon icofont-contact-add"></span>
        </button>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form class="needs-validation" novalidate action="{{ route('admin.users.store') }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add user</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="modal-open" value="#add-user">
                    <div class="file-input form-group avatar-box d-flex justify-content-center align-items-center">
                        <img src="{{ asset('assets/content/user.png') }}" width="80" height="80" alt="Avatar" class="rounded-500 mr-4 img-placeholder">
                        <label class="btn btn-outline-primary h-100" type="button" for="avatar">
                            Choose photo<span class="btn-icon icofont-ui-user ml-2"></span>
                            <input id="avatar" type="file" accept="image/*" name="avatar" class="hidden-input">
                        </label>
                    </div>
                    @error('avatar')
                        <span class="invalid-feedback mb-2" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <h5>Basic Info</h5>
                    <div class="form-group">
                        <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Full name" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <input class="form-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <input class="form-control" type="text" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile" required>
                            @error('mobile')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox mb-3">
                            <input type="checkbox" class="custom-control-input" name="auto_verified" id="verified" checked>
                            <label class="custom-control-label" for="verified">Auto Verify Email</label>
                        </div>
                        @error('mobile')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <h5>Security Info</h5>
                    <div class="row">
                        <div class="form-group col-4">
                            <label>Role</label>
                            <select name="role" id="role" class="selectpicker" title="Role">
                                @foreach(config('system.user_roles') as $role)
                                @if( $role['key'] == 'master' && $auth->isSuperAdmin() )
                                <option value="{{ $role['key'] }}">{{ $role['name'] }}</option>
                                @elseif($auth->isAdmin(false) && $role['key'] != 'master')
                                <option value="{{ $role['key'] }}">{{ $role['name'] }}</option>
                                @endif
                                @endforeach
                            </select>
                            @error('role')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group col-4">
                            <label>Password</label>
                            <input class="form-control" type="password" placeholder="Password" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group col-4">
                            <label>Confirm Password</label>
                            <input class="form-control" type="password" placeholder="Password Confirm" name="password_confirmation" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Add user</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
