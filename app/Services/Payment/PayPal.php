<?php 
namespace App\Services\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use App\Helpers\PayPalClient;
use App\Contracts\PaymentGateway;
/**
 * Paypal Pay
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class PayPal implements PaymentGateway {
    /**
     * PayPal Client Instance
     */
    protected $client;
    /**
     * Store the details
     */
    protected $data;

    function __construct(){
        $this->client = new PayPalClient();
    }

    /**
     * Get the api client instance
     * 
     * @return mixed [description]
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     *  Set the amount
     *
     * @return mixed
     */
    public function setAmount($amount)
    {
        $this->data['amount'] = $amount;
        return $this;
    }

    /**
     *  Set the customer details
     *
     * @return mixed
     */
    public function setCustomer(array $user = [])
    {
        $this->data['customer'] = $user;
        return $this;
    }

    /**
     *  Set the others attributes
     *
     * @return mixed
     */
    public function others(array $others = [])
    {   
        $this->data['others'] = $others;
        return $this;
    }

    /**
     *  Make the payment call
     *
     * @return mixed
     */
    public function payment(Transaction $transaction, Appointment $appointment)
    {
        if($transaction){
            $response = empty($transaction->response) ? ['message' => "Waiting for payment from user."] : $transaction->response;
            $message = 'Transaction created, follow the process to confirm!';
            
            if( ! $transaction->wasRecentlyCreated ){
                $message = 'We are waiting for your confirmation details!';
            }
            if( ! empty($transaction->response['data']['transaction_id'] ?? '') ){
                $message = 'You have already submitted payment details, please wait for approval, or update your payment details here.';
            }
            if( empty($transaction->payment_url) ){
                $result = $this->processPayament($transaction, $appointment);
                $response['paypal'] = $result ? $result : [];
                $link = collect($result->links??[])->where('rel', 'approve')->first();

                $transaction->fill([
                    'response' => $response,
                    'payment_id' => $result->id ?? null,
                    'payment_url' => $link->href ?? null,
                    'status' => 'waiting'
                ])->save();
            }
            return redirect()->route('payment.paypal.process', [
                'appointment' => $appointment->id,
                'transaction' => $transaction->id,
            ])->withInfo($message);
        }

        return [
            'status' => false,
            'message' => "Something is wrong try again!",
        ];
    }

    protected function processPayament($transaction, $appointment)
    {
        $response = $this->client->makePayment($this->convertBdtToUsd($transaction->amount), $transaction->id, $appointment->id);
        if( $response ){
            return $response->result;
        }
        return [];
    }
    
    public function convertBdtToUsd($bdtAmount)
    {
        $rate = (int) settings('payment_currency_rate', config('system.currency.rates.USD', 80)); // 1 USD = 80 BDT
        return round((float)$bdtAmount / (float)$rate, 2);
    }
}
