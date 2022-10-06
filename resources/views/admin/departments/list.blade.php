@extends('layouts.app')
@section('title', 'Departments')

@section('content')
<header class="page-header">
    <h1 class="page-title">Departments</h1>
</header>
<div class="page-content">
    <div class="row">
        @forelse($departments as $department)
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card department bg-light bg-gradient">
                <img src="{{ optional($department->image)->link ?? asset('/assets/content/department.jpg') }}" class="card-img-top" width="400" height="250" alt="">
                <div class="card-body">
                    <h3 class="h4 mt-0">
                        <a href="{{ route('admin.departments.show', $department->id) }}">{{ $department->name }}</a>
                    </h3>
                    <p>{{ $department->description }}</p>
                    <div class="button-box text-right pb-2">
                        <form onsubmit="return confirm('Are you sure?')" action="{{ route('admin.departments.destroy', $department->id) }}" method="POST">
                            <button data-id="{{ $department->id }}" data-link="{{ route('admin.departments.update', $department->id) }}" class="edit-department btn btn-primary btn-sm btn-square rounded-pill" type="button">
                                <span class="btn-icon icofont-ui-edit"></span>
                            </button>
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit">
                                <span class="btn-icon icofont-trash"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <h3>No Item Found</h3>
        @endforelse
        <div class="col-md-12 mt-3">
            {{ $departments->links() }}
        </div>
    </div>
    <div class="add-action-box">
        <button class="btn btn-primary btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-department">
            <span class="btn-icon icofont-plus"></span>
        </button>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="add-department" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.departments.store') }}" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Add department</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="modal-open" value="#add-department">
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Name" name="name" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Descriptions" rows="3" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Add department</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="edit-department" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="javascript:void(0)" method="POST" class="needs-validation" novalidate>
                <div class="modal-header">
                    <h5 class="modal-title">Edit Department</h5>
                </div>
                <div class="modal-body">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal-open" value="#edit-department">
                    <div class="form-group">
                        <input class="form-control" type="text" placeholder="Name" name="name" required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">{{ $message }}
                        @enderror
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Descriptions" rows="3" name="description"></textarea>
                    </div>
                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update department</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush
@push('footer')
<script type="text/javascript">
    var DEPARTMENTS_LIST = @json($departments->getCollection());
    (function($){
        $('.edit-department').on('click', function(){
            var link = $(this).data('link'), id = $(this).data('id'), item = DEPARTMENTS_LIST.find(item=>item.id===id);
            console.log(item);
            if( link && item ){
                $('input[name="name"]').val(item.name);
                $('textarea[name="description"]').val(item.description);
                $('#edit-department').find('form').attr('action', link);
                $('#edit-department').modal('show');
            }
        })
    })(jQuery)
</script>
@endpush
