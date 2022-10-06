@extends('layouts.app')
@section('title', 'Prescriptions Template')
@section('content')
<header class="page-header">
    <h1 class="page-title">Prescriptions Template</h1>
</header>
<div class="page-content">
    <div class="card mb-0">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Problem</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($templates as $template)
                        <tr>
                            <td>{{ $template->title }}</td>
                            <td><div class="text-muted text-ellipsis">{{ $template->chief_complain }}</div></td>
                            <td>
                                <form class="actions" action="{{ route('admin.prescriptions-templates.destroy', ['prescriptions_template'=>$template->id]) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method("DELETE")
                                    <a data-toggle="tooltip" title="Click to use this template" href="{{ route('admin.prescriptions.create', ['template'=>$template->id]) }}" class="btn btn-success btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-copy"></span>
                                    </a>
                                    <a href="{{ route('admin.prescriptions-templates.edit', ['prescriptions_template'=>$template->id]) }}" class="btn btn-info btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-edit"></span>
                                    </a>
                                    <button type="submit" class="btn btn-error btn-sm btn-square rounded-pill">
                                        <span class="btn-icon icofont-ui-delete"></span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">No Prescriptions</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $templates->links() }}
            </div>
        </div>
    </div>
    <div class="add-action-box">
        <a href="{{ route('admin.prescriptions-templates.create') }}" class="btn btn-primary btn-lg btn-square rounded-pill">
            <span class="btn-icon icofont-presentation-alt"></span>
        </a>
    </div>
</div>
@endsection