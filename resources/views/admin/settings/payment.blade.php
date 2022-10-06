<div class="page-content">
    <div class="row justify-content-start">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    Payment Settings
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Payment Tax (If Applicable)</label>
                                <input class="form-control" type="number" name="setting_payment_tax" value="{{ settings('payment_tax', config('system.payment.tax')) }}" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Pay to Doctor (In Percentage %)</label>
                                <input class="form-control" type="number" name="setting_payment_to_doctor" value="{{ settings('payment_to_doctor', config('system.payment.to_doctor')) }}" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Currency Rate (Only for USD - Use for PayPal )</label>
                                <input class="form-control" type="number" name="setting_payment_currency_rate" value="{{ settings('payment_currency_rate', config('system.currency.rates.USD', 81)) }}" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Default Online Payment Method</label>
                                <select name="setting_payment_default_gateway" class="selectpicker" title="Payment Method">
                                    @foreach(array_keys(config('services.payment',[])) as $item)
                                    <option {{ settings('payment_default_gateway')==$item?'selected':'' }} value="{{ $item }}">{{ ucfirst($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <h4>Manual Payment Options</h4>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>bKash Merchant Account Number</label>
                                <input class="form-control" type="text" name="setting_payment_manual_bkash_account" value="{{ settings('payment_manual_bkash_account') }}" placeholder="Manual Payment Bkash account" required>
                            </div>
                            <div class="form-group col-6">
                                <label>Rocket Merchant Account Number</label>
                                <input class="form-control" type="text" name="setting_payment_manual_rocket_account" value="{{ settings('payment_manual_rocket_account') }}" placeholder="Manual Payment Rocket account" required>
                            </div>
                        </div>
                        <hr>

                        <h4>AamarPay Payment Options</h4>
                        <div class="row">
                            <div class="col-12 d-flex">
                                {{-- payment_paypal_sandbox --}}
                                <label class="mr-5">AamarPay Sandbox</label>
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" name="setting_payment_aamarpay_sandbox" value="1" id="sanbox_true" {{ settings('payment_aamarpay_sandbox', env('AAMARPAY_SANDBOX'))=='1'?'checked':'' }}>
                                    <label class="custom-control-label" for="sanbox_true">Enable</label>
                                </div>
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" name="setting_payment_aamarpay_sandbox" value="0" id="sanbox_false" {{ settings('payment_aamarpay_sandbox', config('services.payment.aamarpay.sandbox'))!='1'?'checked':'' }}>
                                    <label class="custom-control-label" for="sanbox_false">Disable</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 col-6">
                                <label>AamarPay Store ID</label>
                                <input class="form-control" type="text" name="setting_payment_aamarpay_client_id" value="{{ settings('payment_aamarpay_client_id', config('services.payment.aamarpay.client_id')) }}" placeholder="AamarPay Client Id" required>
                            </div>
                            <div class="form-group mb-0 col-6">
                                <label>AamarPay Signature</label>
                                <input class="form-control" type="text" name="setting_payment_aamarpay_client_secret" value="{{ settings('payment_aamarpay_client_secret', config('services.payment.aamarpay.client_secret')) }}" placeholder="AamarPay Client Secret" required>
                            </div>
                            <div class="col-md-12 mt-1">
                                You can find your AamarPay credentials from <a target="_blank" href="https://aamarpay.com//">here</a>
                            </div>
                        </div>
                        <hr>
                        <h4>Paypal Payment Options</h4>
                        <div class="row">
                            <div class="col-12 d-flex">
                                {{-- payment_paypal_sandbox --}}
                                <label class="mr-5">Paypal Sandbox</label>
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" name="setting_payment_paypal_sandbox" value="1" id="sanbox_true" {{ settings('payment_paypal_sandbox', env('PAYPAL_SANDBOX'))=='1'?'checked':'' }}>
                                    <label class="custom-control-label" for="sanbox_true">Enable</label>
                                </div>
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" name="setting_payment_paypal_sandbox" value="off" id="sanbox_false" {{ settings('payment_paypal_sandbox', env('PAYPAL_SANDBOX'))!='1'?'checked':'' }}>
                                    <label class="custom-control-label" for="sanbox_false">Disable</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 col-6">
                                <label>Paypal Client ID</label>
                                <input class="form-control" type="text" name="setting_payment_paypal_client_id" value="{{ settings('payment_paypal_client_id', env('PAYPAL_CLIENT_ID')) }}" placeholder="Paypal Client Id" required>
                            </div>
                            <div class="form-group mb-0 col-6">
                                <label>Paypal Client Secret</label>
                                <input class="form-control" type="text" name="setting_payment_paypal_client_secret" value="{{ settings('payment_paypal_client_secret', env('PAYPAL_CLIENT_SECRET')) }}" placeholder="Paypal Client Secret" required>
                            </div>
                            <div class="col-md-12 mt-1">
                                You can find your paypal credentials from <a target="_blank" href="https://developer.paypal.com/">here</a>
                            </div>
                        </div>
                        <hr>
                        <h4>Port Wallet Payment Options</h4>
                        <div class="row">
                            <div class="col-12 d-flex">
                                {{-- payment_paypal_sandbox --}}
                                <label class="mr-5">PortWallet Sandbox</label>
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" name="setting_payment_portwallet_sandbox" value="true" id="sanbox_true" {{ settings('payment_portwallet_sandbox', env('PORTWALLET_SANDBOX'))=='true'?'checked':'' }}>
                                    <label class="custom-control-label" for="sanbox_true">Enable</label>
                                </div>
                                <div class="custom-control custom-radio mr-3">
                                    <input type="radio" class="custom-control-input" name="setting_payment_portwallet_sandbox" value="false" id="sanbox_false" {{ settings('payment_portwallet_sandbox', env('PORTWALLET_SANDBOX'))!='true'?'checked':'' }}>
                                    <label class="custom-control-label" for="sanbox_false">Disable</label>
                                </div>
                            </div>
                            <div class="form-group mb-0 col-6">
                                <label>PortWallet APP Key</label>
                                <input class="form-control" type="text" name="setting_payment_portwallet_app_key" value="{{ settings('payment_portwallet_app_key', env('PAYPAL_CLIENT_ID')) }}" placeholder="PortWallet APP Key" required>
                            </div>
                            <div class="form-group mb-0 col-6">
                                <label>PortWallet APP Secret</label>
                                <input class="form-control" type="text" name="setting_payment_portwallet_app_secret" value="{{ settings('payment_portwallet_app_secret', env('PAYPAL_CLIENT_SECRET')) }}" placeholder="PortWallet APP Secret" required>
                            </div>
                            <div class="col-md-12 mt-1">
                                You can find your PortWallet credentials from <a target="_blank" href="https://developer.portwallet.com/">here</a>
                            </div>
                        </div>
                        <button class="btn mt-3 btn-primary" type="submit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>