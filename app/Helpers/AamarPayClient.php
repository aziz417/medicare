<?php
namespace App\Helpers;

use GuzzleHttp\Client;

/**
 *  AamarPayClient 
 *  
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class AamarPayClient
{
    protected $config = ['sandbox' => true];
    protected $params = [];
    protected $client;
    protected $store = "medicsbd";
    protected $signature = "c1f6dabe7fe65c784f93f7148d2ec797";

    /**
     * Payment constructor.
     *
     * @param $config
     */
    public function __construct( )
    {
        $this->store = settings('payment_aamarpay_client_id', config('services.payment.aamarpay.client_id'));
        $this->signature = settings('payment_aamarpay_client_secret', config('services.payment.aamarpay.client_secret'));
        $this->client = new Client(['base_uri' => $this->getBaseUrl(), 'timeout' => 30, 'verify' => false]);
    }

    public function prepare($config = [])
    {
        $this->config = $config;
        $this->params = $this->setParams($this->config);
        return $this;
    }

    /**
     * Getting Redirect Url
     *
     * @return $this
     */
    public function redirectUrl($success, $failed)
    {
        $this->params[ 'success_url' ] = $success;
        $this->params[ 'fail_url' ] = $failed;
        $this->params[ 'cancel_url' ] = $failed;

        return $this;
    }

    /**
     * Set Currency
     *
     * @param string $currency
     * @return $this
     */
    public function currency( $currency = 'BDT' )
    {
        $this->params[ 'currency' ] = $currency;

        return $this;
    }

    public function makePayment($additional = [])
    {
        $response = $this->client->post("{$this->getBaseUrl()}/request.php", [
            "form_params" => $this->getParams($additional)
        ]);
        $result = $response->getBody()->getContents();
        $paymentPath = is_json($result) ? json_decode($result, true) : $result;
        if( is_string($paymentPath) ){
            return "{$this->getBaseUrl()}{$paymentPath}";
        }
        return $paymentPath;
    }

    public function checkTnx($merchant_id)
    {
        $url = "{$this->getBaseUrl()}/api/v1/trxcheck/request.php";

        $response = $this->client->get($url,[
            "query" => [
                'request_id' => $merchant_id,
                'store_id' => $this->store,
                'signature_key' => $this->signature,
                'type' => "json",
            ]
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function getBaseUrl()
    {

        $sandbox = settings('payment_aamarpay_sandbox', config('services.payment.aamarpay.sandbox'));
        if ( $sandbox ) {
            return 'https://sandbox.aamarpay.com';
        } else {
            return 'https://secure.aamarpay.com';
        }
    }

    protected function setParams($params)
    {
        $data = $this->validateData($params, config('app.debug'));
        return [
            'store_id' => $this->store,
            'signature_key' => $this->signature,
            'amount' => $data['amount'],
            'currency' => 'BDT',
            'tran_id' => $data['transaction'] ?? "#".rand(1000, 9999),
            'cus_name' => $data['name'],
            'cus_email' => $data['email'],
            'cus_phone' => $data['mobile'],

            'desc' => $data['description'],
            'opt_a' => $data['user_id'],

            'success_url' => $data['success_url'] ?? null,
            'fail_url' => $data['failed_url'] ?? null,
            'cancel_url' => $data['failed_url'] ?? null,
        ];
    }

    protected function validateData($data, $dummy = false)
    {
        $items = ['amount', 'transaction', 'name', 'email', 'mobile', 'description', 'user_id'];
        foreach ($items as $item) {
            if( ! array_key_exists($item, $data) ){
                if( $dummy ){
                    if( empty($data[$item]) ){
                        $data[$item] = $data[$item] ?? $item;
                    }
                }else{
                    throw new \Exception("$item must be need to provide!");
                    return false;
                }
            }
        }
        return $data;
    }

    protected function getParams($additional = []) : array
    {
        return array_merge($this->params, $additional);
    }

}

// http://sandbox.aamarpay.com/api/v1/trxcheck/request.php?request_id=%23312313&store_id=aamarpay&signature_key=28c78bb1f45112f5d40b956fe104645e&type=json

// https://secure.aamarpay.com/api/v1/trxcheck/request.php?request_id=%2330671047-TNX33-870&store_id=medicsbd&signature_key=c1f6dabe7fe65c784f93f7148d2ec797&type=json

// $fields = array(
//     'store_id' => $this->user,
//     'signature_key' => $this->signature
//     'amount' => $amount,
//     'currency' => 'BDT',
//     'tran_id' => $id,
//     'cus_name' => $data['name'],
//     'cus_email' => $data['email'],
//     'cus_phone' => $data['mobile'],

//     // 'cus_add1' => $data['address'],
//     // 'cus_city' => $data['city'] ?? $data['address'],
//     // 'cus_state' => $data['state'] ?? $data['address'],
//     // 'cus_postcode' => $data['postcode'] ?? $data['address'],
//     // 'cus_country' => 'Bangladesh',

//     'desc' => 'Appointment Payment',
//     'success_url' => base_url("payment/appointment/{$id}/success"),
//     'fail_url' => base_url("payment/appointment/{$id}/failed"),
//     'cancel_url' => base_url("payment/appointment/{$id}/cancel"),
//     'opt_a' => $data['user_id'],
// );
// 
// Success Data
//   "pg_service_charge_bdt" => "2.10"
// "pg_service_charge_usd" => "Not-Available"
// "pg_card_bank_name" => null
// "pg_card_bank_country" => null
// "card_number" => "01826323538"
// "card_holder" => null
// "cus_phone" => "01644973410"
// "desc" => null
// "success_url" => "http://medicsbd.test/test"
// "fail_url" => "http://medicsbd.test/test"
// "cus_name" => "Akash"
// "cus_email" => "akash@user.com"
// "currency_merchant" => "BDT"
// "convertion_rate" => null
// "ip_address" => "116.204.148.108"
// "other_currency" => "100.00"
// "pay_status" => "Successful"
// "pg_txnid" => "AAM1606586324116075"
// "epw_txnid" => "AAM1606586324116075"
// "mer_txnid" => "#312313"
// "store_id" => "aamarpay"
// "merchant_id" => "aamarpay"
// "currency" => "BDT"
// "store_amount" => "97.90"
// "pay_time" => "2020-11-28 23:59:11"
// "amount" => "100.00"
// "bank_txn" => "6ED4FIJ1G0"
// "card_type" => "bKash-bKash"
// "reason" => null
// "pg_card_risklevel" => null
// "pg_error_code_details" => null
// "opt_a" => "1"
// "opt_b" => null
// "opt_c" => null
// "opt_d" => null