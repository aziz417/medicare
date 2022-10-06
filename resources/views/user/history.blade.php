@extends('layouts.app')
@section('title', 'Health History')

@section('content')
<div class="page-content">
    <div class="card mb-0">
        <div class="card-header">My Health History
            <button data-toggle="modal" data-target="#add-new-history" class="float-right btn btn-primary">Add New</button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="20%" scope="col">Title</th>
                            <th  scope="col">Details</th>
                            <th width="20%" scope="col">Date</th>
                            <th width="10%" scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $item)
                        <tr>
                            <td>
                                <div class="text-muted text-nowrap">
                                    <strong>{{ $item->title }}</strong> 
                                </div>
                            </td>
                            <td>
                                <div class="read-more-box" data-text="{{ $item->details }}">
                                    {{ html_string(word_limit($item->details, 20, '...<a href="javascript:void(0)" class="read-more">more</a>')) }}
                                </div>
                            </td>
                            <td>
                                <div class="text-muted text-nowrap">
                                    {{ _date($item->date ?? $item->created_at)->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <form onsubmit="return confirm('Are you sure?')" action="{{ route('user.history.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" data-link="{{ route('user.history.update', $item->id) }}" data-id="{{ $item->id }}" class="btn btn-info btn-sm btn-square edit-button rounded-pill"><span class="btn-icon icofont-edit"></span></button>
                                    <button type="submit" class="btn btn-danger btn-sm btn-square rounded-pill"><span class="btn-icon icofont-trash"></span></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">No Appointment</td>
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
<div class="modal fade" id="add-new-history" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="needs-validation" novalidate action="{{ route('user.history.store') }}" method="POST" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Add New History</h5>
                </div>

                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="modal-open" value="#add-new-history">
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="title" class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="Title" list="patient-history" required>
                        <datalist id="patient-history">
                            @foreach(config('system.patient_history', []) as $item)
                            <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </datalist>
                        @error('title')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="details">Details</label>
                        <textarea name="details" id="details" cols="30" rows="3" class="form-control" placeholder="Write something...">{{ old('details') }}</textarea>
                        @error('details')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" value="{{ date('Y-m-d') }}" class="form-control datepicker" name="date" placeholder="Date">
                        @error('date')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Add History</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-history" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form class="needs-validation" novalidate action="javascript:void(0)" method="POST" autocomplete="off">
                <div class="modal-header">
                    <h5 class="modal-title">Edit History</h5>
                </div>

                <div class="modal-body">
                    @csrf @method("PUT")
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input id="edit-title" class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="Title" list="patient-history" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="details">Details</label>
                        <textarea name="details" id="edit-details" cols="30" rows="3" class="form-control" placeholder="Write something...">{{ old('details') }}</textarea>
                        @error('details')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input id="edit-date" type="date" value="{{ date('Y-m-d') }}" class="form-control datepicker" name="date" placeholder="Date">
                        @error('date')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer d-block">
                    <div class="actions justify-content-between">
                        <button type="button" class="btn btn-error" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info">Update History</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('footer')
<script type="text/javascript">
    const HISTORIES = @json($history->getCollection());

    (function($){
        $('button.edit-button').on('click', function(){
            let ID = $(this).data('id'), LINK = $(this).data('link');
            let ITEM = HISTORIES.find(item=>item.id===ID);

            if( ITEM ){
                let date = new Date(ITEM.date||ITEM.created_at);
                $('#edit-history #edit-title').val(ITEM.title)
                $('#edit-history #edit-details').val(ITEM.details)
                $('#edit-history #edit-date').val(date?.toLocaleDateString('fr-CA', {year: 'numeric', month: '2-digit', day: '2-digit'}))
                $('#edit-history form').attr('action', LINK);
                $('#edit-history').modal('show');
            }
        })
    })(jQuery)
</script>
@endpush