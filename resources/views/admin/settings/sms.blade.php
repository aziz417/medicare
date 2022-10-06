<div class="page-content">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    SMS Settings
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings') }}" method="POST">
                        @csrf
                        <h4>Default Gateway</h4>
                        <div class="form-group">
                            <label for="">Set Default SMS Driver</label>
                            <select name="setting_sms_default_gateway" class="selectpicker" title="SMS Gateway">
                                @foreach(array_keys(config('services.sms',[])) as $item)
                                <option {{ settings('sms_default_gateway')==$item?'selected':'' }} value="{{ $item }}">{{ ucfirst($item) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <h4>Clickatell API</h4>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>API Key</label>
                                <input class="form-control" type="text" name="setting_sms_clickatell_api_key" value="{{ settings('sms_clickatell_api_key', config('services.sms.clickatell.api_key')) }}" placeholder="API Key">
                            </div>
                            <div class="form-group col-6">
                                <label>Sender Name</label>
                                <input class="form-control" type="text" name="setting_sms_clickatell_sender" value="{{ settings('sms_clickatell_sender', config('services.sms.clickatell.sender')) }}" placeholder="Sender Name">
                            </div>
                            <div class="col-12">Get you Clickatell credential <a href="https://www.clickatell.com/" target="_blank">here</a></div>
                        </div>
                        <hr>
                        <h4>Nexmo API</h4>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Sender Name</label>
                                <input class="form-control" type="text" name="setting_sms_nexmo_sender" value="{{ settings('sms_nexmo_sender', config('services.sms.nexmo.sender')) }}" placeholder="Sender Name">
                            </div>
                            <div class="form-group col-6">
                                <label>API Key</label>
                                <input class="form-control" type="text" name="setting_sms_nexmo_api_key" value="{{ settings('sms_nexmo_api_key', config('services.sms.nexmo.api_key')) }}" placeholder="API Key">
                            </div>
                            <div class="form-group col-6">
                                <label>API Secret</label>
                                <input class="form-control" type="text" name="setting_sms_nexmo_api_secret" value="{{ settings('sms_nexmo_api_secret', config('services.sms.nexmo.api_secret')) }}" placeholder="API Secret">
                            </div>
                            <div class="col-md-12">Get your Nexmo credentials <a href="https://www.nexmo.com/" target="_blank">here</a></div>
                        </div> 
                        <hr>
                        <h4>Jadu API</h4>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>API Key</label>
                                <input class="form-control" type="text" name="setting_sms_jadu_api_key" value="{{ settings('sms_jadu_api_key', config('services.sms.jadu.api_key')) }}" placeholder="API Key">
                            </div>
                            <div class="form-group col-6">
                                <label>Sender Name</label>
                                <input class="form-control" type="text" name="setting_sms_jadu_sender" value="{{ settings('sms_jadu_sender', config('services.sms.jadu.sender')) }}" placeholder="Sender Name">
                            </div>
                            <div class="col-md-12">Get your Jadu credentials <a href="http://jadusms.com/" target="_blank">here</a></div>
                        </div>
                        <hr>
                        <h4>Twilio API</h4>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Twilio Phone Number</label>
                                <input class="form-control" type="text" name="setting_sms_twilio_sender" value="{{ settings('sms_twilio_sender', config('services.sms.twilio.sender')) }}" placeholder="Sender Name/Number">
                            </div>
                            <div class="form-group col-6">
                                <label>Twilio SID</label>
                                <input class="form-control" type="text" name="setting_sms_twilio_sid" value="{{ settings('sms_twilio_sid', config('services.sms.twilio.sid')) }}" placeholder="Twilio SID">
                            </div>
                            <div class="form-group col-6">
                                <label>API Token</label>
                                <input class="form-control" type="text" name="setting_sms_twilio_token" value="{{ settings('sms_twilio_token', config('services.sms.twilio.token')) }}" placeholder="API Token">
                            </div>
                            <div class="col-md-12">Get your Twilio credentials <a href="https://www.twilio.com/" target="_blank">here</a></div>
                        </div> 
                        
                        <button class="btn btn-primary mt-3" type="submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
