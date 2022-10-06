<?php 
namespace App\Helpers;

use PayPalHttp\HttpException;
use App\Contracts\PaymentGateway;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
/**
 * Paypal Client
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class PayPalClient {
    protected $client;

    function __construct(){
        $this->client = $this->client();
    }

    public function makePayment($amount, $transaction, $appointment)
    {
        // Construct a request object and set desired parameters
        // Here, OrdersCreateRequest() creates a POST request to /v2/checkout/orders
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
             "intent" => "CAPTURE",
             "purchase_units" => [[
                 "reference_id" => "Transaction ID #{$transaction}",
                 "amount" => [
                     "value" => $amount,
                     "currency_code" => "USD"
                 ]
             ]],
             "application_context" => [
                "cancel_url" => route('payment.paypal.cancel', [
                    'transaction' => $transaction, 'appointment' => $appointment
                ]),
                "return_url" => route('payment.paypal.success', [
                    'transaction' => $transaction, 'appointment' => $appointment
                ])
            ] 
        ];

        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response;
        }catch (HttpException $ex) {
            return false;
        }
        return false;
    }

    public function getDetails($orderId) 
    {
        try {
            $response = $this->client->execute(new OrdersGetRequest($orderId));
            return $response->result;
        } catch (HttpException $e) {
            return false;
        }
        return false;
    }

    public function callback($orderId)
    {
        // Here, OrdersCaptureRequest() creates a POST request to /v2/checkout/orders
        // $response->result->id gives the orderId of the order created above
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');
        try {
            // Call API with your client and get a response for your call
            $response = $this->client->execute($request);
            // If call returns body in response, you can get the deserialized version from the result attribute of the response
            return $response->result;
        }catch (HttpException $ex) {
            return false;
        }
        return false;
    }

    /**
     * Returns PayPal HTTP client instance with environment that has access
     * credentials context. Use this instance to invoke PayPal APIs, provided the
     * credentials have access.
     */
    protected function client()
    {
        return new PayPalHttpClient($this->environment());
    }

    /**
     * Set up and return PayPal PHP SDK environment with PayPal access credentials.
     * This sample uses SandboxEnvironment. In production, use LiveEnvironment.
     */
    protected function environment()
    {
        $clientId = config('services.payment.paypal.client_id', env("PAYPAL_CLIENT_ID"));
        $clientSecret = config('services.payment.paypal.client_secret', env("PAYPAL_CLIENT_SECRET"));
        if( config('services.payment.paypal.sandbox', false) ){
            return new SandboxEnvironment($clientId, $clientSecret); // SandBox
        }else{
            return new ProductionEnvironment($clientId, $clientSecret); // Production
        }
    }
}

// install package 
// composer require paypal/paypal-checkout-sdk 1.0.1
// SandBox Account
// Email ID: sb-ntyht3213912@personal.example.com
// Password: 4!zBa6eU