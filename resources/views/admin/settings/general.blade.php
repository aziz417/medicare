<div class="page-content">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    General Settings
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>App Name</label>
                            <input class="form-control" type="text" name="setting_app_name" value="{{ settings('app_name', config('app.name')) }}" required>
                        </div>
                        <div class="form-group">
                            <label>App Tagline</label>
                            <input class="form-control" type="text" name="setting_app_tagline" value="{{ settings('app_tagline', "") }}">
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input class="form-control" type="text" name="setting_app_address" value="{{ settings('app_address') }}" placeholder="Location">
                        </div>
                        <div class="form-group">
                            <label>Support Email</label>
                            <input class="form-control" type="text" name="setting_app_email" value="{{ settings('app_email') }}" placeholder="Support Email">
                        </div>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>