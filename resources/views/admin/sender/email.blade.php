@extends('layouts.app')
@section('title', 'Email Sender')

@push('header')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/simplemde/simplemde.min.css') }}">
@endpush

@section('content')
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Send Email to User
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sender.email.send') }}" method="POST" class="row justify-content-center">
                        @csrf
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="user" class="control-label">Select User</label>
                                <select multiple name="users[]" id="user" class="selectpicker" data-live-search="true">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-subtext="{{ ucfirst($user->role) }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('users')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="template" class="control-label">Select Template</label>
                                <select name="template" id="template" class="selectpicker" data-live-search="true">
                                    <option value="">Select Template</option>
                                    @foreach($templates as $template)
                                    <option value="{{ $template->key }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                                @error('template')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <input type="text" class="form-control" name="subject" id="subject" placeholder="Email Subject">
                            </div>
                            <div class="form-group">
                                <label for="content" class="control-label">Content</label>
                                <textarea name="content" id="content" cols="30" rows="4" class="form-control" placeholder="Write sowmthing..."></textarea>
                                @error('content')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                                <span class="text-muted">Use editor preview for preview! Header & Footer and Short-code will be auto compile or load while sending...
                                </span>
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
<script type="text/javascript" src="{{ asset('assets/libs/simplemde/simplemde.min.js') }}"></script>
<script type="text/javascript">
    const TEMPLATES = @json($templates);
    (function($){
        loadEditor(true);
        $('#template').on('change', function(){
            let value = $(this).val();
            if( value ){
                let template = TEMPLATES.find(item=>item.key===value);
                if( template ){
                    window.mdEditor.value(template.content);
                    $('#subject').val(template.subject)
                }
            }
        })
    })(jQuery)
    function loadEditor(status){
        if( status ){
            window.mdEditor = new SimpleMDE({ 
                element: document.getElementById("content"),
                hideIcons: ["guide", 'quote', 'side-by-side','fullscreen', 'table'],
                showIcons: ['horizontal-rule']
            });
            window.mdEditor.codemirror.on("change", function(){
                $('#content').val(window.mdEditor.value());
            });
        }else{
            if( window.mdEditor!==null ){
                window.mdEditor.toTextArea();
                window.mdEditor = null;
            }
        }
    }
</script>
@endpush