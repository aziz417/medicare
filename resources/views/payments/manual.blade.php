@extends('layouts.app')
@section('title', "Verify Manual Payment")
@php( $methods = array_keys(config('system.payment.gateway_methods.manual', [])) )

@section('content')
<header class="page-header">
    <h1 class="page-title">To verify your payment complete the step below.</h1>
</header>
<div class="page-content">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form class="row" action="{{ route('payment.manual.verify', ['appointment'=>$appointment->id, 'transaction'=>$transaction->id]) }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="col-md-6">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="method">Method</label>
                                    <select name="method" id="method" class="selectpicker">
                                        @foreach($methods as $item)
                                        <option {{ $transaction->method==$item ? 'selected':'' }} value="{{ $item }}">{{ ucfirst($item) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="account">Enter Account number</label>
                                    <input class="form-control" type="text" name="account_number" id="account" placeholder="Enter your account number, where from you pay." value="{{ $transaction->response['data']['account_number'] ?? old('account_number') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="tnx_id">Enter Transaction ID</label>
                                    <input class="form-control" type="text" name="transaction_id" id="tnx_id" placeholder="Enter TNX ID" value="{{ $transaction->response['data']['transaction_id'] ?? old('transaction_id') }}" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="amount">Paid Amount</label>
                                    <input class="form-control" type="text" name="amount" id="amount" placeholder="Enter paid amount" value="{{ $transaction->final_amount }}">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="comment">Comment</label>
                                    <input class="form-control" type="text" name="comment" id="comment" placeholder="Write something..." value="{{ $transaction->response['data']['comment'] ?? old('comment') }}">
                                </div>
                                <div class="col-md-12">
                                    <div class="float-right">
                                        <a href="{{ route('user.appointments.show', $appointment->id) }}" class="btn btn-warning">Back</a>
                                        <button type="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Payment Process for <div class="text-capitalize" id="method-name">{{ ucfirst($methods[0] ?? '') }}</div></h4>
                            <ol class="instructions bkash {{ ($methods[0] ?? '') == 'bkash' ?'': 'd-none' }}">
                                <li>Go to bKash Menu by dialing *247#</li>
                                <li>Choose 'Payment' option by pressing '3'</li>
                                <li>Enter our Merchant wallet number : {{ settings('payment_manual_bkash_account', config('system.payment.manual.bkash')) }}.</li>
                                <li>Enter BDT: {{ inCurrency($transaction->final_amount) }}</li>
                                <li>Enter a reference against your payment : {{ $appointment->appointment_code }}</li>
                                <li>Enter the counter number : 1.</li>
                                <li>Now enter your PIN to confirm: xxxxx.</li>
                                <li>Done! You will get a confirmation SMS </li>
                                <li>Enter your bKash wallet/contact number and transaction ID in the form and submit.</li>
                            </ol>
                            <ol class="instructions rocket {{ ($methods[0] ?? '') == 'rocket' ?'': 'd-none' }}">
                                <li>Go to your Rocket Mobile Menu by dialing *322#</li>
                                <li>Choose Bill Pay option</li>
                                <li>Choose Self or Others</li>
                                <li>Choose 0. “Other” (Go to “0.Other” option )</li>
                                <li>Enter Biller ID number : {{ settings('payment_manual_rocket_account', config('system.payment.manual.rocket')) }}</li>
                                <li>Enter Your Bill Number: {{ $appointment->appointment_code }}.</li>
                                <li>Enter the bill amount : BDT {{ inCurrency($transaction->final_amount) }}.</li>
                                <li>Now enter your Rocket Mobile Menu “PIN” to confirm</li>
                                <li>Done! You will receive a confirmation message from 16216</li>
                                <li>Enter your bKash wallet/contact number and transaction ID in the form and submit.</li>
                            </ol>
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
    (function($){
        $('#method').on('change', function(){
            var $this = $(this);
            if( $('.instructions').hasClass($this.val()) ){
                $('.instructions').toggleClass('d-none');
            }else{
                $('.instructions').toggleClass('d-none');
            }
            $('#method-name').text($this.val());
        })
    })(jQuery)
</script>
@endpush