@extends('layouts.app')
@section('title', 'SMS Sender')
@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Send SMS to User
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sender.sms.send') }}" method="POST" class="row justify-content-center">
                        @csrf
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user" class="control-label">Select User</label>
                                <select multiple name="users[]" id="user" class="selectpicker" data-live-search="true">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-subtext="{{ ucfirst($user->role) }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="template" class="control-label">Select Template</label>
                                <select name="template" id="template" class="selectpicker" data-live-search="true">
                                    <option value="">Select Template</option>
                                    @foreach($templates as $template)
                                    <option value="{{ $template->key }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="content" class="control-label">Content</label>
                                <textarea name="content" id="content" cols="30" rows="4" class="form-control" placeholder="Write SMS..."></textarea>
                                <span class="text-muted">Short-code will be auto fill while sending...</span>
                            </div>
                            <div class="form-group">
                                <label>Preview</label>
                                <div style="white-space: break-spaces;" id="preview" class="border p-3">Select a template!</div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="float-right btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('footer')
<script type="text/javascript">
    const TEMPLATES = @json($templates);
    (function($){
        $('#template').on('change', function(){
            let value = $(this).val();
            if( value ){
                let template = TEMPLATES.find(item=>item.key===value);
                if( template ){
                    $('#content').val(template.content);
                    $('#preview').text(template.content);
                }
            }
        })
        $('#content').on('keyup change', function(){
            let value = $(this).val();
            $('#preview').text(value);
        })
    })(jQuery)
</script>
@endpush