@extends('layouts.app')
@section('title', 'Advice')

@push('header')
<style type="text/css">
    .slot-item {
        border-bottom: 1px solid #999;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">Advice
    </h1>
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
                                    <th scope="col">Title</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($advices as $advice)
                                    <tr>
                                        <td class="d-flex">
                                            <div class="ml-2">
                                                <small>{{ $advice->title ?? 'N/A' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-start">
                                                <a href="{{ route('admin.advices.edit', $advice->id) }}" class="btn btn-info btn-sm btn-square rounded-pill"><span class="btn-icon icofont-ui-edit"></span></a>
                                                <form title="ID: {{ $advice->id }}" class="actions" method="post" action="{{ route('admin.advices.destroy', $advice->id) }}" onsubmit="return confirm('Are you sure?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger btn-sm btn-square rounded-pill" type="submit"><span class="btn-icon icofont-trash"></span></button>
                                                </form>
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                <tr>
                                    <td colspan="7">No Patient Found!</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $advices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-action-box">
        <button class="btn btn-dark btn-lg btn-square rounded-pill" data-toggle="modal" data-target="#add-advice">
            <span class="btn-icon icofont-plus-circle"></span>
        </button>
    </div>
</div>
@endsection

@push('modal')
<div class="modal fade" id="add-advice" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <form class="modal-content needs-validation" novalidate action="{{ route('admin.advices.store') }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Add Advice</h5>
            </div>
            <div class="modal-body">
                @csrf
                <input type="hidden" name="modal-open" value="#add-advice">
                <div id="slot-list">
                    <div class="row border-bottom mb-3">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input class="form-control" type="text" name="title" placeholder="Title" value="{{ old('title') }}" required>
                                @error('title')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-block">
                <div class="actions justify-content-between">
                    <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add advice</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

</script>
@endpush
