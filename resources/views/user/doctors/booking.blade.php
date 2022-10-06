@extends('layouts.app')
@section('title', 'Booking')

@push('header')
<style type="text/css">
    input.invisible-checkbox {
        visibility: hidden;
    }
    .time-slot-item:hover input.invisible-checkbox:checked + span::before {
        color: #fff;
    }
    input.invisible-checkbox:checked + span::before {
        color: #000;
        content: "\eed8";
        font-family: IcoFont !important;
        font-size: 20px;
        font-weight: 900;
        position: absolute;
        left: 0px;
        top: 50%;
        transform: translate(50%, -50%);
    }
    .page-header {
        border: 1px solid #ebebeb;
    }
    .doctor-info {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        justify-content: left;
        align-items: center;
    }
    .doctor-info .doctor-img {
        width: 80px;
        margin-right: 15px;
    }
    .doctor-info .doctor-img img {
        border-radius: 4px;
        height: 80px;
        width: 80px;
        object-fit: cover;
    }
    ul.wizard-nav {
        display: flex;
        justify-content: center;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 0px;
    }
    .wizard-nav a.nav-link.active {border-top: 2px solid #336cfb;}
</style>
@endpush
@section('content')
<form method="POST" action="{{ route('user.doctors.booking', $doctor->id) }}">
    <header class="page-header">
        <div class="card card m-4 w-100">
            <div class="card-body d-flex align-items-center justify-content-between">
                <div class="doctor-info">
                    <a href="{{ route('user.doctors.show', $doctor->id) }}" class="doctor-img">
                        <img src="{{ asset($doctor->avatar()) }}" alt="{{ $doctor->name }}">
                    </a>
                    <div class="booking-info">
                        <h4 class="m-0"><a href="{{ route('user.doctors.show', $doctor->id) }}">{{ $doctor->name }}</a></h4>
                        <p class="text-muted mb-0">{{ strip_tags($doctor->getMeta('user_designation')) }} <br>
                            <strong>{{ $doctor->department->name }}</strong>
                        </p>
                    </div>
                </div>
                <div class="appointment-info">
                    <a href="{{ route('user.appointments.create') }}" class="float-right btn btn-sm btn-warning">Back</a>
                </div>
            </div>
        </div>
    </header>
    <div class="page-content">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-0">
                    @csrf
                    <div id="wizard" class="mt-3">
                        <ul class="nav wizard-nav nav-tabs" role="tablist">
                            <li role="presentation" class="nav-item">
                                <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1" class="nav-link active">Choose Slot</a>
                            </li>
                            <li role="presentation" class="nav-item">
                                <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2" class="nav-link disabled">Basic Info</a>
                            </li>
                            <li role="presentation" class="nav-item">
                                <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Final Step" class="nav-link disabled">Appointment Overview</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="schedule-widget tab-pane active" role="tabpanel" id="step1">
                                <div class="schedule-header">
                                    <div class="schedule-nav">
                                        <ul class="nav nav-tabs nav-justified">
                                            @foreach($dates as $date)
                                            <li class="nav-item">
                                                <a class="nav-link {{ isDay(ucfirst($date->format('l')), 'active') }}" data-toggle="tab" data-day="{{ ucfirst($date->format('l')) }}" href="#slot_{{ strtolower($date->format('l')) }}">
                                                    <span data-toggle="tooltip" title="{{ $date->format('d M, Y') }}">{{ ucfirst($date->format('l')) }}</span>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="tab-content schedule-cont">
                                    @foreach($dates as $date)
                                        <div id="slot_{{ strtolower($date->format('l')) }}" class="tab-pane fade {{ isDay(ucfirst($date->format('l')), 'active show') }}">
                                            <h4 class="card-title d-flex justify-content-between">
                                                <span> {{ $date->format('l, d M, Y') }} - Time Slots</span>
                                            </h4>
                                            @if( $schedules->has(ucfirst($date->format('l'))) )
                                            <div class="doc-times">
                                                @foreach($schedules->get(ucfirst($date->format('l'))) as $schedule)
                                                    @foreach($schedule->getTimeSlots() as $slot)

                                                    @php( $slotTime = "{$date->format('Y-m-d')} {$slot->format('H:i')}" )
                                                    @php( $booked = isSameDateTime($appointments, $slotTime) )
                                                    @php( $isAvailableTime = now()->lessThanOrEqualTo($slotTime) )

                                                    @if($isAvailableTime)
                                                    <label data-toggle="tooltip" title="{{ $booked ? 'Already Booked': "Session Duration: {$schedule->duration} minutes" }}" class="btn {{ $booked ? 'btn-danger disabled' : 'btn-outline-dark' }} m-2 time-slot-item">
                                                        <input class="invisible-checkbox scheduled_at" name="scheduled_at" type="radio" value="{{ $slotTime }}" {{ $booked ? 'disabled':'' }} data-schedule_id="{{ $schedule->id }}" data-error_message="Select a slot first!" required />
                                                        <span>{{ $slot->format('h:i A') }}</span>
                                                    </label>
                                                    @endif

                                                    @endforeach
                                                @endforeach
                                                <span id="slot-message"></span>
                                            </div>
                                            @else
                                            <p class="text-muted mb-0">Not Available</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="schedule_id" id="schedule_id">
                                <button type="button" class="float-right next-step btn btn-success">Next</button>
                            </div>
                            <div class="tab-pane" id="step2" role="tabpanel">
                                <h2>Basic Info</h2>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="appointment">Appointment For</label>
                                        <select name="patient_id" id="appointment" class="selectpicker" required>
                                            <option value="{{ $auth->id }}" data-subtext="{{ $auth->name }}">Self</option>
                                            @foreach( $subMembers as $member )
                                            <option value="{{ $member->id }}" data-subtext="{{ $member->getMeta('relationship_with_member', 'Relation') }}">{{ $member->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="type"><span class="mr-3">Appointment Type</span> - <span class="ml-3" id="booking-charge">Charge: {{ inCurrency($doctor->getCharge('booking')->amount??0) }}</span></label>
                                        <select name="type" id="type" class="selectpicker" required>
                                            <option data-subtext="{{ inCurrency($doctor->getCharge('booking')->amount??0) }}" {{ old('type')=='booking' ?'selected' :'' }} value="booking">Booking</option>
                                            <option data-subtext="{{ inCurrency($doctor->getCharge('reappoint')->amount??0) }}" {{ old('type')=='reappoint' ?'selected' :'' }} value="reappoint">Re Appointment</option>
                                            <option data-subtext="{{ inCurrency($doctor->getCharge('report')->amount??0) }}" {{ old('type')=='report' ?'selected' :'' }} value="report">Report Showing</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="coupon">Coupon Code</label>
                                        <input type="text" name="coupon_code" class="form-control" id="coupon_code" placeholder="Coupon Code">
                                        <input type="hidden" id="charge_amount" name="charge_amount" value="{{@$doctor->getCharge('booking')->amount}}">
                                        <input type="hidden" id="discount" name="discount">
                                        <div id="coupon-code-msg"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="problem">Problem Details</label>
                                        <textarea name="patient_problem" id="problem" cols="30" rows="2" class="form-control" placeholder="Write Something..." data-error_message="Write something about your problem!" required></textarea>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="payment">Payment Method</label>
                                        <select name="payment_gateway" id="payment" class="selectpicker" required>
                                            <option value="">Select Method</option>
                                            <option value="online">Online</option>
                                            <option value="manual">Manual</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="payment_method" id="method">
                                    {{-- <div class="form-group col-md-3">
                                        <label for="method">Payment Method</label>
                                        <select name="payment_method" id="method" class="selectpicker" required>
                                            <option value="">Select Gateway First</option>
                                        </select>
                                    </div> --}}
                                </div>
                                <div class="mt-3">
                                    <button type="button" class="float-right btn-overview next-step btn btn-success">Next</button>
                                </div>
                            </div>
                            <div class="tab-pane" id="step3" role="tabpanel">
                                <h3>Overview</h3>
                                <table class="table list-table overview-table">
                                    <tbody>
                                        <tr>
                                            <td>Appointment Type</td>
                                            <td>:</td>
                                            <td class="text-capitalize" id="ovh-type"></td>
                                        </tr>
                                        <tr>
                                            <td>Appointment Date</td>
                                            <td>:</td>
                                            <td id="ovh-date"></td>
                                        </tr>
                                        <tr>
                                            <td>Appointment Time</td>
                                            <td>:</td>
                                            <td id="ovh-time"></td>
                                        </tr>
                                        <tr>
                                            <td>Charge Amount</td>
                                            <td>:</td>
                                            <td id="ovh-amount"></td>
                                        </tr>
                                        <tr>
                                            <td>Coupon Code</td>
                                            <td>:</td>
                                            <td id="ovh-coupon"></td>
                                        </tr>
                                        <tr>
                                            <td>Payable Amount</td>
                                            <td>:</td>
                                            <td id="ovh-final-amount"></td>
                                        </tr>
                                        <tr>
                                            <td>Payment Gateway</td>
                                            <td>:</td>
                                            <td class="text-capitalize" id="ovh-payment-method"></td>
                                        </tr>
                                        <tr>
                                            <td>Problem Summery</td>
                                            <td>:</td>
                                            <td id="ovh-details"></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="mt-3">
                                    <button type="submit" class="float-right btn btn-success">Confirm & Proceed to Payment</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('footer')
<script type="text/javascript">
    const DOCTOR = @json($doctor_json);
    const CURRENCY = @json(config('system.currency'));
    const PAYMENT_METHOD = @json(config('system.payment.gateway_methods'));

    function addCurrency(amount){
        return `${CURRENCY.symbol}${amount}`;
    }
    $('.scheduled_at').on('change', function(){
        $('#schedule_id').val($(this).data('schedule_id'));
    });
    $('#type').on('change', function(){
        var charge = DOCTOR.charges.find(item=>item.type===$(this).val());
        $('#booking-charge').text("Charge: "+ addCurrency(charge.amount ?? 0));
        $('#charge_amount').val(charge.amount);
    });
    $('#payment').on('change', function(){
        let gateway = this.value;
        loadMethod(gateway);
    });
    $(document).ready(function(){
        if( $('#payment').val() ){
            loadMethod($('#payment').val())
        }
    });
    function loadMethod(gateway){
        if( gateway === 'manual' ){
            $('#method').val('manual')
        }else{
            $('#method').val('{{ settings('payment_default_gateway', config('system.payment.gateway', 'aamarpay')) }}')
        }

        // let methods = PAYMENT_METHOD[gateway];
        // let items = Object.keys(methods).map(item=>{
        //     return `<option value="${item}">${methods[item]}</option>`;
        // });
        // let options = ['<option value="">Select Method</option>', ...items];
        // $('#method').html(options.join(''));
        // $('#method').selectpicker('refresh');
    }


    $('#coupon_code').on('keyup', function(){
        var $this = $(this);
        if( $this.val().length > 2 ){
            $.get(`{{ route('api.discount.check') }}?coupon_code=${$this.val()}&user_id=${__App.user.id}&charge=${$('#charge_amount').val()}`)
            .then(response=>{
                if( response.status ){
                    $('#discount').val(response.discount);
                    $('#coupon-code-msg').addClass('text-success').removeClass('text-danger').text(response.message)
                }else{
                    $('#coupon-code-msg').addClass('text-danger').removeClass('text-success').text(response.message)
                }
            }).catch(err=>{
                console.log(err);
                showToaster(err?.responseJSON?.message ?? 'Something is wrong!', {title:'Coupon code error!'});
            })
        }
    });

    $('#wizard .wizard-nav a[href="#step3"]').on('show.bs.tab', function (e) {
        var charge = DOCTOR.charges.find(item=>item.type===$('#type').val());
        $('#ovh-type').text( $('#type').val() );
        $('#ovh-date').text( moment($('.scheduled_at:checked').val()).format('DD MMM YYYY') );
        $('#ovh-time').text( moment($('.scheduled_at:checked').val()).format('hh:mm A') );
        $('#ovh-amount').text( addCurrency(charge.amount ?? 00) );
        $('#ovh-coupon').text( $('#coupon_code').val() || 'N/A' );
        $('#ovh-final-amount').text( addCurrency(Number(charge.amount??0) - Number($('#discount').val())) );
        $('#ovh-details').text($('#problem').val());
        $('#ovh-payment-method').text( $('#payment').val() + " ("+ $('#method').val() +")" );
    })
    $('#wizard .wizard-nav a[data-toggle="tab"]').on('show.bs.tab', function (e) {
         var $target = $(e.target);
         if ($target.hasClass('disabled')) {
             return false;
         }
     });

     $("#wizard .next-step").click(function (e) {
        var $active = $('#wizard .wizard-nav .nav-item .active');
        var $activeli = $active.parent("li");
        var isValidated = true;
        var $inputs = $(this).parents(".tab-pane").find(":input");
        $inputs.each(function () {
            if ( !this.checkValidity() && isValidated) {
                isValidated = false;
                console.log(this.dataset);
                showToaster( this.dataset?.error_message || 'Fill / Choose required item!', {title: "Validation Error!"});
                try{
                    this.focus();
                }catch(err){
                    //
                }
            }
        });

        if( isValidated ){
            $($activeli).next().find('a[data-toggle="tab"]').removeClass("disabled");
            $($activeli).next().find('a[data-toggle="tab"]').click();
        }
     });

     $("#wizard .prev-step").click(function (e) {
         var $active = $('#wizard .wizard-nav .nav-item .active');
         var $activeli = $active.parent("li");
         $($activeli).prev().find('a[data-toggle="tab"]').removeClass("disabled");
         $($activeli).prev().find('a[data-toggle="tab"]').click();
     });
</script>
@endpush
