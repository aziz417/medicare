@extends('layouts.app')
@section('title', 'Patients')

@push('header')
<style type="text/css">
    .sub-member {
        border-left: 2px solid #999;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">Patients</h1>
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
                                    <th scope="col">Gender</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($patients as $patient)
                                    @include('admin.patients.list-item', ['patient' => $patient])
                                @empty
                                <tr>
                                    <td colspan="7">No Patient Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $patients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-action-box">
        <button class="btn btn-dark btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-patient">
            <span class="btn-icon icofont-contact-add"></span>
        </button>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="add-patient" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form class="needs-validation" novalidate action="{{ route('admin.patients.store') }}" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add patient</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="modal-open" value="#add-patient">
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
                    <div class="row">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <input class="form-control" type="number" placeholder="Age" name="user_age" value="{{ old('user_age') }}">
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <select name="user_gender" class="selectpicker" title="Gender">
                                    <option class="d-none">Gender</option>
                                    <option>Male</option>
                                    <option>Female</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <textarea class="form-control" name="user_address" placeholder="Address" placeholder="Enter Address" rows="3"></textarea>
                    </div>
                    <h5>Security Info</h5>
                    <div class="row">
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
                        <button type="submit" class="btn btn-info">Add patient</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
