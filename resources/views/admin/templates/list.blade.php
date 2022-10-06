@extends('layouts.app')
@section('title', 'Templates')

@push('header')
<style type="text/css">
    .floating-dropdown .floating-menu {
        display: none;
    }
    .floating-dropdown .floating-menu.show {
        display: block;
    }
    .floating-dropdown .floating-item {
        border-bottom: 1px solid #ddd;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">Email & SMS Templates</h1>
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
                                    <th scope="col">Key</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($templates as $item)
                                <tr>
                                    <td>
                                        <div class="text-muted">{{ sprintf('%02s', $loop->iteration)  }}</div>
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->key }}</td>
                                    <td>{{ $item->type }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <form onsubmit="return confirm('Are you sure?')" action="{{ route('admin.templates.destroy', $item->id) }}" method="POST">
                                                <a href="{{ route('admin.templates.show', $item->id) }}" class="edit-item btn btn-primary btn-sm btn-square rounded-pill">
                                                    <span class="btn-icon icofont-eye"></span>
                                                </a>
                                                <a href="{{ route('admin.templates.edit', $item->id) }}" class="edit-item btn btn-primary btn-sm btn-square rounded-pill">
                                                    <span class="btn-icon icofont-ui-edit"></span>
                                                </a>
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
                                    <td colspan="9">No Template Found!</td>
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
        <a href="{{ route('admin.templates.create') }}" class="floating-toggle btn btn-primary btn-lg btn-square rounded-pill">
            <span class="btn-icon icofont-plus"></span>
        </a>
    </div>
</div>
@endsection

@push('footer')
<script type="text/javascript">
    (function($){
        $('.floating-toggle').on('click', function(){
            let $menu = $(this).parents('.floating-dropdown').find('.floating-menu');
            $menu.toggleClass('show');
        })
    })(jQuery)
</script>
@endpush