@extends('layouts.app')
@section('title', 'Sub Members')

@section('content')
    <div class="page-content">
        <div class="card mb-0">
            <div class="card-header">My Sub Members
                <button data-toggle="modal" data-target="#add-new-member" class="float-right btn btn-primary">Add New
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Member</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($members as $item)
                            <tr>
                                <td>
                                    <div class="table-user d-flex align-items-center">
                                        <div class="img-circle">
                                            <img
                                                src="{{ asset(optional($item)->avatar() ?? 'assets/content/user.png') }}"
                                                alt="{{$item->name ?? ''}}" class="rounded-500">
                                        </div>
                                        <div class="ml-2">
                                            <strong>{{ $item->name ?? 'N/A' }}</strong><br>
                                            <small><span title="Relationship"
                                                         class="icon-responsive icofont-infinite p-0"></span>
                                                {{ optional($item)->getMeta('relationship_with_member', 'N/A') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column justify-content-start">
                                        <div class="text-info font-weight-bold"><span
                                                class="icofont-ui-cell-phone p-0 mr-2"></span> {{ $item->mobile }}</div>
                                        <div class="text-primary"><span
                                                class="icofont-ui-email p-0 mr-2"></span> {{ $item->email }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted text-nowrap">
                                        {{ optional($item)->getMeta('user_gender', 'N/A') }}
                                    </div>
                                </td>
                                <td>
                                    <form
                                        onsubmit="return confirm('Are you sure?\nIf you delete this, all related data(appointment, prescription & etc) will be lost!')"
                                        action="{{ route('user.sub-members.destroy', $item->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="button"
                                                class="btn btn-info btn-sm btn-square rounded-pill edit-member"
                                                data-link="{{ route('user.sub-members.update', $item->id) }}"
                                                data-id="{{ $item->id }}"><span class="btn-icon icofont-pencil"></span>
                                        </button>
                                        <button type="submit" class="btn btn-danger btn-sm btn-square rounded-pill">
                                            <span class="btn-icon icofont-trash"></span></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Sub Members, Create one!</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{-- <x-chat-box appointment="12" /> --}}
@endsection

@push('modal')
    <div class="modal fade" id="add-new-member" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="needs-validation" novalidate action="{{ route('user.sub-members.store') }}" method="POST"
                      autocomplete="off" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Member</h5>
                    </div>

                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="modal-open" value="#add-new-member">
                        <div class="alert alert-warning p-2">You can add
                            maximum {{ config('system.max_sub_members_of_a_member', '~') }} members!
                        </div>
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
                        <h5>Member Info</h5>
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
                                       placeholder="Email">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="gender" id="gender" class="selectpicker" required>
                                        <option value="">Gender</option>
                                        <option {{ old('gender')=='Male' ? 'selected':'' }} value="Male">Male</option>
                                        <option {{ old('gender')=='Female' ? 'selected':'' }} value="Female">Female
                                        </option>
                                        <option {{ old('gender')=='Other' ? 'selected':'' }} value="Other">Other
                                        </option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="relationship_with_member" id="relationship_with_member"
                                            class="selectpicker" required>
                                        <option value="">Relationship</option>
                                        @foreach(config('system.sub_members', []) as $item)
                                            <option
                                                {{ old('relationship_with_member') == $item ? 'selected':'' }} value="{{$item}}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error('relationship_with_member')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <input class="form-control" type="text" placeholder="Blood Group"
                                       name="user_blood_group"
                                       value="{{ $auth->getMeta('user_blood_group') ?? old('user_blood_group') }}">
                                @error('user_blood_group')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <input class="form-control" type="number" placeholder="Age" name="user_age"
                                       value="{{ $auth->getMeta('user_age') ?? old('user_age') }}">
                                @error('user_age')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="actions justify-content-between">
                            <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">Add Member</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit-member" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form class="needs-validation" novalidate
                      action="{{ route('user.sub-members.update', old('update_id') ?? 0) }}" method="POST"
                      enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Member</h5>
                    </div>

                    <div class="modal-body">
                        @csrf @method('PUT')
                        <input type="hidden" name="modal-open" value="#edit-member">
                        <input type="hidden" name="update_id" value="">
                        <div
                            class="file-input img-box form-group avatar-box d-flex justify-content-center align-items-center">
                            <img id="edit-image" src="{{ asset('assets/content/user.png') }}" width="80" height="80"
                                 alt="Avatar" class="rounded-500 mr-4 img-placeholder">
                            <label class="btn btn-outline-primary h-100" type="button" for="edit-avatar">
                                Choose photo<span class="btn-icon icofont-ui-user ml-2"></span>
                                <input id="edit-avatar" type="file" accept="image/*" name="avatar" class="hidden-input">
                            </label>
                        </div>
                        @error('avatar')
                        <span class="invalid-feedback mb-2" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <h5>Member Info</h5>
                        <div class="form-group">
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}"
                                   placeholder="Full name" id="edit-name" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <input class="form-control" id="edit-email" type="email" name="email"
                                       value="{{ old('email') }}" placeholder="Email">
                                @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <input class="form-control" type="text" id="edit-mobile" name="mobile"
                                       value="{{ old('mobile') }}" placeholder="Mobile">
                                @error('mobile')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="gender" id="edit-gender" class="selectpicker" required>
                                        <option value="">Gender</option>
                                        <option {{ old('gender')=='Male' ? 'selected':'' }} value="Male">Male</option>
                                        <option {{ old('gender')=='Female' ? 'selected':'' }} value="Female">Female
                                        </option>
                                        <option {{ old('gender')=='Other' ? 'selected':'' }} value="Other">Other
                                        </option>
                                    </select>
                                    @error('gender')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <select name="relationship_with_member" id="edit-relationship_with_member"
                                            class="selectpicker" required>
                                        <option value="">Relationship</option>
                                        @foreach(config('system.sub_members', []) as $item)
                                            <option
                                                {{ old('relationship_with_member') == $item ? 'selected':'' }} value="{{$item}}">{{ $item }}</option>
                                        @endforeach
                                    </select>
                                    @error('relationship_with_member')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <input class="form-control" id="edit-user-blood-group" type="text" name="user_blood_group"
                                       value="{{ old('user_blood_group') }}" placeholder="Blood Group">
                                @error('user_blood_group')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <input class="form-control" id="edit-user-age" type="text" name="user_age"
                                       value="{{ old('user_age') }}" placeholder="Age">
                                @error('user_age')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-block">
                        <div class="actions justify-content-between">
                            <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-info">Update Member</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@push('footer')
    <script type="text/javascript">
        var MEMBERS_LIST = @json($members->map(function($item){return $item->getPublicData('meta');})->toArray());
        (function ($) {
            $('.edit-member').on('click', function () {
                var link = $(this).data('link'), id = $(this).data('id'),
                    item = MEMBERS_LIST.find(item => item.id === id);
                if (link && item) {
                    $('#edit-member input[name="update_id"]').val(item.id);
                    $('#edit-member input[name="name"]').val(item.name);
                    $('#edit-member input[name="name"]').val(item.name);
                    $('#edit-member img#edit-image').attr('src', item.picture);
                    $('#edit-member input[name="email"]').val(item.email);
                    $('#edit-member input[name="user_age"]').val(item.meta.user_age);
                    $('#edit-member input[name="user_blood_group"]').val(item.meta.user_blood_group);

                    $gender = $('#edit-member select[name="gender"]');
                    $gender.find(`option[value="${item.meta?.user_gender}"]`).attr('selected');
                    $gender.selectpicker('refresh');
                    $relationship = $('#edit-member select[name="relationship_with_member"]');
                    $relationship.find(`option[value="${item.meta?.relationship_with_member}"]`).attr('selected');
                    $relationship.selectpicker('refresh');

                    $('#edit-member').find('form').attr('action', link);
                    $('#edit-member').modal('show');
                }
            })
        })(jQuery)
    </script>
@endpush
