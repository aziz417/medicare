@extends('layouts.app')
@section('title', 'View Template')

@push('header')
<style type="text/css">
    iframe {
        min-height: 60vh;
        max-height: 100vh;
        height: 100%;
    }
</style>
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title"><strong>{{ $template->name }}</strong></h1>
    <div class="float-right d-flex btn-group h-100">
        <a href="{{ route('admin.templates.edit', $template->id) }}" class="btn btn-sm btn-warning">Edit</a>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-sm btn-info">Back</a>
    </div>
</header>
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if( $template->type == 'email' )
            <iframe class="show-template" src="{{ route('admin.templates.show', ['template'=>$template->id, 'show'=>'template']) }}" frameborder="0" width="100%" height="100%"></iframe>
            @else
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="card bg-light border-secondary">
                        <div class="card-body">
                            {{ $template->compiled([
                                '[[OTP]]' => rand(1111, 9999)
                            ],$auth) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection