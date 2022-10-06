@extends('layouts.app')
@section('title', (isset($template) ? "Edit " : "New ")."Template")

@push('header')
<style type="text/css">
    .tags {
        display: flex;
        justify-content: start;
        align-items: center;
        flex-flow: wrap;
    }
    .tags .tag-item {
        border: 1px solid #336cfb;
        color: #336cfb;
        border-radius: 20px;
        padding: 5px 10px;
        margin: 2px;
        font-weight: 600;
        cursor: pointer;
    }
</style>
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/simplemde/simplemde.min.css') }}">
@endpush

@section('content')
<header class="page-header">
    <h1 class="page-title">{{isset($template) ? "Edit ": "Create "}} Template</h1>
    <div class="float-right d-flex btn-group h-100">
        @isset($template)
        <a href="{{ route('admin.templates.show', $template->id) }}" class="btn btn-sm btn-primary">Preview</a>
        @endisset
        <a href="{{ route('admin.templates.index') }}" class="btn btn-sm btn-info">Back</a>
    </div>
</header>
<div class="page-content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($template) ? route('admin.templates.update', $template->id) : route('admin.templates.store') }}" method="POST" class="row justify-content-center">
                        @csrf 
                        @isset($template)
                        @method("PUT")
                        @endisset
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="control-label">Name</label>
                                <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" value="{{ isset($template) ? $template->name : old('name') }}" required>
                                @error('name')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="type" class="control-label">Type</label>
                                @isset($template)
                                <input name="type" type="text" value="{{ $template->type }}" class="text-capitalize form-control" readonly>
                                @else
                                <select name="type" id="type" class="selectpicker">
                                    <option {{ isset($template) ? ($template->type=="sms"?'selected':'') : '' }} value="sms">SMS</option>
                                    <option {{ isset($template) ? ($template->type=="email"?'selected':'') : '' }} value="email">Email</option>
                                </select>
                                @endisset
                                @error('type')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="content" class="control-label">Content</label>
                                <textarea name="content" id="content" placeholder="Content..." cols="30" rows="3" class="form-control" required>{{ isset($template) ? $template->content : old('content') }}</textarea>
                                @error('content')
                                <span class="invalid-feedback" role="alert">{{ $message }}
                                @endif
                            </div>
                            <div id="email-template" class="{{ isset($template) ? ($template->type=='sms'?'d-none':'') : 'd-none'}}">
                                <div class="form-group">
                                    <label for="subject" class="control-label">Subject</label>
                                    <input type="text" id="subject" name="subject" class="form-control" placeholder="Enter Subject" value="{{ isset($template) ? $template->subject : old('subject') }}">
                                    @error('subject')
                                    <span class="invalid-feedback" role="alert">{{ $message }}
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-8">
                                        <label for="route" class="control-label">URL / Route Name</label>
                                        <input type="text" name="action[path]" class="form-control" id="route" placeholder="route:home or fresh url..." value="{{ isset($template) ? $template->action['path']??'' : old('action',[])['path']??'' }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="action_title" class="control-label">Action Button Title</label>
                                        <input type="text" name="action[title]" class="form-control" id="action_title" placeholder="ex: Home" value="{{ isset($template) ? $template->action['title']??'' : old('action',[])['title']??'' }}">
                                    </div>
                                    @error('action')
                                    <div class="col-md-12">
                                        <span class="invalid-feedback" role="alert">{{ $message }}
                                    </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="after" class="control-label">Text after action button</label>
                                    <textarea name="after" id="after" placeholder="Write something..." cols="30" rows="3" class="form-control">{{ isset($template) ? $template->after : old('after') }}</textarea>
                                    @error('after')
                                    <span class="invalid-feedback" role="alert">{{ $message }}
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="float-right btn btn-primary">Submit</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <h3>Shortcodes</h3>
                            <p>Available Shortcodes</p>
                            <div class="tags">
                                {{-- App Data --}}
                                @foreach(config('system.shortcodes', []) as $item)
                                <div data-toggle="tooltip" title="Click to copy!" class="tag-item flash-btn">{{$item}}</div>
                                @endforeach
                            </div>
                            <p class="mt-3 text-muted">
                                <b>Important Note:</b>
                                <ul>
                                    <li>Some shortcodes are not performed to all templates like as: [[OTP]]. <br> These are available only for specific item.</li>
                                    <li>For email action button use <b><i>route:</i></b> prefix for route, NB: parameter route is not supported here!</li>
                                    <li>You can use markdown character when you can select template type as Email</li>
                                </ul>
                            </p>
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
    @isset($template)
    loadEditor(true);
    @endisset
    $('#type').on('change', function(){
        var $this = $(this);
        if( $this.val() === "email" ){
            $("#email-template").removeClass('d-none');
            loadEditor(true);
        }else{
            loadEditor(false);
            $("#email-template").addClass('d-none')
        }
    });
    $('.tag-item').on('click', function(){
        copyText(this.innerText);
        $(this)
            .attr("title", "Copied!")
            .tooltip("_fixTitle")
            .tooltip("show")
            .attr("title", "Click to copy!")
            .tooltip("_fixTitle");
    });
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