<div class="page-content">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Email Settings
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>SMTP Host</label>
                            <input class="form-control" type="text" name="setting_email_smtp_host" value="{{ settings('email_smtp_host',env('MAIL_HOST')) }}" placeholder="SMTP Host" required>
                        </div>
                        <div class="form-group">
                            <label>SMTP Port</label>
                            <input class="form-control" type="text" name="setting_email_smtp_port" value="{{ settings('email_smtp_port',env('MAIL_PORT')) }}" placeholder="SMTP Port">
                        </div>
                        <div class="form-group">
                            <label>SMTP User</label>
                            <input class="form-control" type="text" name="setting_email_smtp_user" value="{{ settings('email_smtp_user',env('MAIL_USERNAME')) }}" placeholder="SMTP User">
                        </div>
                        <div class="form-group">
                            <label>SMTP Password</label>
                            <input class="form-control" type="text" name="setting_email_smtp_password" value="{{ settings('email_smtp_password',env('MAIL_PASSWORD')) }}" placeholder="SMTP Password">
                        </div>
                        <div class="form-group">
                            <label>SMTP Encryption</label>
                            <input class="form-control" type="text" name="setting_email_smtp_encription" value="{{ settings('email_smtp_encription',env('MAIL_ENCRYPTION')) }}" placeholder="SMTP Encryption">
                        </div>
                        <div class="form-group">
                            <label>Email From Name</label>
                            <input class="form-control" type="text" name="setting_email_from_name" value="{{ settings('email_from_name',env('MAIL_FROM_NAME')) }}" placeholder="Email From Name">
                        </div>
                        <div class="form-group">
                            <label>Email From Address</label>
                            <input class="form-control" type="text" name="setting_email_from_address" value="{{ settings('email_from_address',env('MAIL_FROM_ADDRESS')) }}" placeholder="Email From Address">
                        </div>

                        <button class="btn btn-primary" type="submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>