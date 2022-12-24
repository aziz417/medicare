

@extends('layouts.app')
@section('title', 'ICD')

@push('header')
    <style type="text/css">
        .slot-item {
            border-bottom: 1px solid #999;
        }
    </style>
@endpush

@section('content')
    <header class="page-header">
        <h1 class="page-title">ICD
        </h1>
    </header>

        <form class="modal-content needs-validation" novalidate action="{{ route('admin.icds.update', $icd->id) }}" method="POST">
            <div class="modal-header">
                <h5 class="modal-title">Edit ICD</h5>
            </div>
            <div class="modal-body">
                @csrf
                @method("put")
                <input type="hidden" name="modal-open" value="#add-advice">
                <div id="slot-list">
                    <div class="row border-bottom mb-3">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="">Title</label>
                                <input class="form-control" type="text" name="title" placeholder="Title" value="{{ $icd->title }}" required>
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
                    <a href="{{ route('admin.icds.index') }}" class="btn btn-error">Cancel</a>
                    <button type="submit" class="btn btn-info">Update ICD</button>
                </div>
            </div>
        </form>

@endsection
