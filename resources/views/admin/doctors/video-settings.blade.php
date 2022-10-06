@extends('layouts.app')
@section('title', 'Doctors Video Settings')

@section('content')
<header class="page-header">
    <h1 class="page-title">{{ $auth->name }}'s Video Settings</h1>
</header>
<div class="page-content">
    <div class="row">
        <div class="col col-md-6">
            <div class="card mb-0 ">
                <div class="card-header">Settings</div>
                <div class="card-body">
                    <form action="{{ url()->current() }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="provider" class="control-label">Provider</label>
                            <select name="provider" id="provider" class="selectpicker ">
                                <option {{$auth->getMeta('video_call_provider')=='jitsi' ? 'selected':''}} value="jitsi">Medcrypter</option>
                                <option {{$auth->getMeta('video_call_provider')=='zoom' ? 'selected':''}} value="zoom">Zoom</option>
                            </select>
                        </div>
                        <div class="mt-3">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @if( $auth->getMeta('video_call_provider')=='zoom' )
        <div class="col col-md-6">
            <div class="card mb-0 ">
                <div class="card-header">Zoom Settings</div>
                <div class="card-body">
                    <p>Your Zoom JWT App Credentials.</p>
                    <form action="{{ route('admin.video.zoom.settings') }}" method="POST">
                        @csrf
                        @php( $zoomData = $auth->getMeta('zoom_meeting_credentials',[]) )
                        {{-- apiKey apiSecret meetingNumber passWord --}}
                        <div class="form-group">
                            <label for="apiKey" class="control-label">API Key</label>
                            <input class="form-control" name="apiKey" value="{{ $zoomData['apiKey'] ?? old('apiKey') }}" placeholder="API Key" required />
                        </div>
                        <div class="form-group">
                            <label for="apiSecret" class="control-label">API Secret</label>
                            <input class="form-control" name="apiSecret" value="{{ $zoomData['apiSecret'] ?? old('apiSecret') }}" placeholder="API Secret" required />
                        </div>
                        <div class="form-group">
                            <label for="meetingNumber" class="control-label">Meeting ID/Number</label>
                            <input class="form-control" name="meetingNumber" value="{{ $zoomData['meetingNumber'] ?? old('meetingNumber') }}" placeholder="Meeting ID/Number" required />
                        </div>
                        <div class="form-group">
                            <label for="passWord" class="control-label">Meeting Password</label>
                            <input class="form-control" name="passWord" value="{{ $zoomData['passWord'] ?? old('passWord') }}" placeholder="Meeting Password (If any)" />
                        </div>
                        <p><a href="https://marketplace.zoom.us/docs/guides/build/jwt-app" target="_blank">Here</a> you can find how to build an JWT App on zoom, and <a href="https://marketplace.zoom.us/develop/create" target="_blank">here</a> you can create your JWT App.</p>
                        <div class="mt-3">
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection