@extends('layouts.app')
@section('title', 'Badges')

@section('content')
<header class="page-header">
    <h1 class="page-title">Badges</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr class="bg-primary text-white">
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Color</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($badges as $item)
                                <tr>
                                    <td>
                                        <div class="text-muted">{{ sprintf('%02s', $loop->iteration)  }}</div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>
                                        <div class="text-muted text-nowrap"><div class="badge badge-{{ $item->color }}">{{ ucfirst($item->color) }}</div></div>
                                        </td>
                                    <td>
                                        <div class="btn-group">
                                            <form onsubmit="return confirm('Are you sure?')" action="{{ route('admin.badges.destroy', $item->id) }}" method="POST">
                                                <button data-id="{{ $item->id }}" data-link="{{ route('admin.badges.update', $item->id) }}" class="edit-item btn btn-primary btn-sm btn-square rounded-pill" type="button">
                                                    <span class="btn-icon icofont-ui-edit"></span>
                                                </button>
                                                @csrf @method('DELETE')
                                                <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit">
                                                    <span class="btn-icon icofont-trash"></span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9">No Coupon Code Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="add-action-box">
        <button class="btn btn-primary btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-badge">
            <span class="btn-icon icofont-plus"></span>
        </button>
    </div>
</div>
@endsection


@push('modal')
<div class="modal fade" id="add-badge" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.badges.store') }}" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Add badge</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="modal-open" value="#add-badge">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" type="text" placeholder="Name" value="{{ old('name') }}" name="name" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <select name="color" class="selectpicker">
                            <option value="primary"><span class="text-primary">Primary</span></option>
                            <option value="secondary"><span class="text-secondary">Secondary</span></option>
                            <option value="info"><span class="text-info">Info</span></option>
                            <option value="warning"><span class="text-warning">Warning</span></option>
                            <option value="danger"><span class="text-danger">Danger</span></option>
                            <option value="default"><span class="text-default">Default</span></option>
                        </select>
                        @error('color')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" placeholder="Description" value="{{ old('description') }}" name="description" required>
                        @error('description')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Add badge</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-badge" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="javascript:void(0)" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Badge</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal-open" value="#edit-badge">
                    <div class="form-group">
                        <label>Name</label>
                        <input class="form-control" type="text" placeholder="Name" value="{{ old('name') }}" name="name" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Color</label>
                        <select name="color" class="selectpicker">
                            <option value="primary"><span class="text-primary">Primary</span></option>
                            <option value="secondary"><span class="text-secondary">Secondary</span></option>
                            <option value="info"><span class="text-info">Info</span></option>
                            <option value="warning"><span class="text-warning">Warning</span></option>
                            <option value="danger"><span class="text-danger">Danger</span></option>
                            <option value="default"><span class="text-default">Default</span></option>
                        </select>
                        @error('color')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input class="form-control" type="text" placeholder="Description" value="{{ old('description') }}" name="description" required>
                        @error('description')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update badge</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@push('footer')
<script type="text/javascript">
    var BADGES_LIST = @json($badges->getCollection());
    (function($){
        $('.edit-item').on('click', function(){
            var link = $(this).data('link'), id = $(this).data('id'), item = BADGES_LIST.find(item=>item.id===id);
            console.log(item);
            if( link && item ){
                $('#edit-badge input[name=name]').val(item.name);
                $select = $('#edit-badge input[name=color]').find('option[value='+item.color+']');
                $select.attr('selected', 'selected');
                $select.selectpicker('refresh');
                $('#edit-badge input[name=description]').val(item.description);
                
                $('#edit-badge').find('form').attr('action', link);
                $('#edit-badge').modal('show');
            }
        })
    })(jQuery)
</script>
@endpush