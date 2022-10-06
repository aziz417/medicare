<div class="row">
    <div class="col-6">
        <div class="card mb-0 ">
            <div class="card-header">Additional Info</div>
            <div class="card-body">
                <h6 class="mt-0 mb-0">Designation</h6>
                <p>⇒ {{ $auth->getMeta('user_designation') }}</p>
                <h6 class="mt-0 mb-0">Department</h6>
                <p>⇒ {{ $auth->department->name ?? '~' }}</p>
                <h6 class="mt-0 mb-0">Specialization</h6>
                <p>⇒ {{ $auth->getMeta('user_specialization') ?? '~' }}</p>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card mb-0 ">
            <div class="card-header">Appointment Charges</div>
            <div class="card-body">
                <h6 class="mt-0 mb-0">New Appointment Booking</h6>
                {{-- → ⇒ --}}
                <p>⇒ {{ inCurrency($auth->getCharge('booking')->amount) }}</p>
                <h6 class="mt-0 mb-0">Re-Appointment (within 30 days)</h6>
                <p>⇒ {{ inCurrency($auth->getCharge('reappoint')->amount) }}</p>
                <h6 class="mt-0 mb-0">Report Showing</h6>
                <p>⇒ {{ inCurrency($auth->getCharge('report')->amount) }}</p>
            </div>
        </div>
    </div>
</div>