<div class="row">
    <div class="col-6">
        <div class="card mb-0 ">
            <div class="card-header">Additional Info</div>
            <div class="card-body">
                <h6 class="mt-0 mb-0">Age</h6>
                <p>⇒ {{ $auth->getMeta('user_age') }}</p>
                <h6 class="mt-0 mb-0">Gender</h6>
                <p>⇒ {{ $auth->getMeta('user_gender') }}</p>
                <h6 class="mt-0 mb-0">Blood Group</h6>
                <p>⇒ {{ strtoupper($auth->getMeta('user_blood_group')) }}</p>
            </div>
        </div>
    </div>
</div>