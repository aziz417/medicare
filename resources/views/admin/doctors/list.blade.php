@extends('layouts.app')
@section('title', 'Doctors')

@section('content')
    <header class="page-header">
        <h1 class="page-title">Doctors</h1>
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
                                    <th scope="col">Doctor</th>
                                    <th scope="col">Department</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Mobile</th>
                                    <th scope="col">Desk Doctor</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($doctors as $doctor)
                                    <tr>
                                        <td style="min-width: 200px;" class="d-flex">
                                            <div class="img-circle">
                                                <img src="{{ asset($doctor->avatar()) }}" alt="{{ $doctor->name }}"
                                                     class="rounded-500">
                                            </div>
                                            <div class="ml-2 doctor-name">
                                                <strong>{{ $doctor->name }}</strong> <br>
                                                {{ $doctor->getMeta('user_designation', '~') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted">
                                                @forelse($doctor->departments as $department)
                                                    <a class="my-3" href="{{ route('admin.departments.show', $department->id ?? 0) }}"><strong>{{ $department->name ?? '~' }}</strong></a>
                                                    ,
                                                    <br/>
                                                @empty
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center nowrap text-primary"><span
                                                        class="icofont-ui-email p-0 mr-2"></span> {{ $doctor->email }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center nowrap text-primary"><span
                                                        class="icofont-ui-cell-phone p-0 mr-2"></span> {{ $doctor->mobile }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center nowrap text-primary"> {{ $doctor->is_desk_doctor ?'Yes':'No' }}</div>
                                        </td>
                                        <td>
                                            <div class="text-muted text-nowrap">
                                                <div class="badge badge-{{ $doctor->status=='active' ? 'success': 'warning' }}">{{ ucfirst($doctor->status ?? 'active') }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <form class="actions"
                                                  action="{{ route('admin.doctors.destroy', $doctor->id) }}"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf @method('DELETE')
                                                <a href="{{ route('admin.doctors.show', $doctor->id) }}"
                                                   class="btn btn-primary btn-sm btn-square rounded-pill"><span
                                                            class="btn-icon icofont-eye-alt"></span></a>
                                                @can('edit-doctor', $doctor)
                                                    <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                                       class="btn btn-info btn-sm btn-square rounded-pill"><span
                                                                class="btn-icon icofont-ui-edit"></span></a>
                                                @endcan
                                                @can('delete-doctor', $doctor)
                                                    <button class="btn btn-danger btn-sm btn-square rounded-pill"
                                                            type="submit"><span class="btn-icon icofont-trash"></span>
                                                    </button>
                                                @endcan
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">No Doctor found!</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                        <nav class="mt-4">
                            {{ $doctors->links() }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        {{--
        <div class="row">
            @foreach($doctors as $doctor)
            <div class="col-12 col-md-4">
                <div class="contact">
                    <div class="img-box">
                        <img src="{{ asset($doctor->avatar()) }}" width="400" height="400" alt="{{ $doctor->name }}">
                    </div>
                    <div class="info-box">
                        <h4 class="name">{{ $doctor->name }}</h4>
                        <p class="role">
                            {{ $doctor->getMeta('user_designation', '~') }} <br>
                            <strong>{{ $doctor->department->name ?? '~' }}</strong>
                        </p>
                        <p class="address">{{ autop($doctor->getMeta('user_address') ) }}</p>
                        <div class="button-box">
                            <form class="actions" action="{{ route('admin.doctors.destroy', $doctor->id) }}" onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <a href="{{ route('admin.doctors.show', $doctor->id) }}" class="btn btn-primary btn-square rounded-pill">View</a>
                                @if($auth->isAdmin(false))
                                <a href="{{ route('admin.doctors.edit', $doctor->id) }}" class="btn btn-info btn-square rounded-pill">Edit</a>
                                <button class="btn btn-danger btn-square rounded-pill" type="submit">Delete</button>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="col-md-12 mt-4">
                {{ $doctors->links() }}
            </div>
        </div>
         --}}

        <div class="add-action-box">
            <button class="btn btn-dark btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-doctor">
                <span class="btn-icon icofont-contact-add"></span>
            </button>
        </div>
    </div>
@endsection

@push('modal')
    <div class="modal fade" id="add-doctor" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form class="needs-validation" novalidate action="{{ route('admin.doctors.store') }}" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add doctor</h5>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="modal-open" value="#add-doctor">
                        <div class="file-input form-group avatar-box d-flex justify-content-center align-items-center">
                            <img src="{{ asset('assets/content/user.png') }}" width="80" height="80" alt="Avatar"
                                 class="rounded-500 mr-4 img-placeholder">
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
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                   placeholder="Full name" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <input class="form-control" type="email" name="email" value="{{ old('email') }}"
                                       placeholder="Email" required>
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <input class="form-control" type="text" name="mobile" value="{{ old('mobile') }}"
                                       placeholder="Mobile">
                                @error('mobile')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="designation"
                                           value="{{ old('designation') }}" placeholder="Designation" required>
                                    @error('designation')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-sm-12">
                                <div class="form-group">
                                    <select title="Department" multiple="multiple" name="department_id[]"
                                            id="department" class="selectpicker" required>
                                        @foreach($departments ?? [] as $item)
                                            <option {{ old('department_id')==$item->id ? 'selected':'' }} value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="address" placeholder="Address"
                                      rows="3">{{ old('address') }}</textarea>
                        </div>
                        <h5>Security Info</h5>
                        <div class="row">
                            <div class="form-group col-4">
                                <label>Password</label>
                                <input class="form-control" type="password" placeholder="Password" name="password"
                                       required>
                                @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-4">
                                <label>Confirm Password</label>
                                <input class="form-control" type="password" placeholder="Password Confirm"
                                       name="password_confirmation" required>
                            </div>
                        </div>
                        <h5>Appointment Charges</h5>
                        <div class="row">
                            <div class="form-group col-4">
                                <label>Booking</label>
                                <input class="form-control" type="number" placeholder="New Appointment"
                                       name="charge_booking" value="{{ old('charge_booking') }}" required>
                                @error('charge_booking')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-4">
                                <label>Re-Appointment</label>
                                <input class="form-control" type="number" placeholder="Re-Appointment"
                                       name="charge_reappoint" value="{{ old('charge_reappoint') }}" required>
                                @error('charge_reappoint')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-4">
                                <label>Report Showing</label>
                                <input class="form-control" type="number" placeholder="Report show" name="charge_report"
                                       value="{{ old('charge_report') }}" required>
                                @error('charge_report')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="actions justify-content-between">
                            <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">Add doctor</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@push('script')
    <script>
        $(document).ready(function () {
            $('.department_id').select2();
        });
    </script>
@endpush

