<?php 
namespace App\Services\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use App\Helpers\AamarPayClient;
use App\Contracts\PaymentGateway;
/**
 * AamarPay
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class AamarPay implements PaymentGateway {
    /**
     * Store the details
     */
    protected $data = [];
    protected $client;
    protected $payment_id;

    function __construct(){
        $this->client = new AamarPayClient();
        $this->payment_id = rand(1000, 9999);
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
        $this->data['name'] = $user['name'];
        $this->data['email'] = $user['email'];
        $this->data['mobile'] = $user['mobile'];

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
            if( ! empty($transaction->response['payment_url'] ?? '') ){
                $message = 'You have already submitted payment details, please wait for approval, or update your payment details here.';
            }
            if( empty($transaction->payment_url) ){
                $payment_url = $this->processPayament($transaction, $appointment);
                if( is_string($payment_url) ){
                    $transaction->fill([
                        'response' => ['payment_url' => $payment_url],
                        'payment_id' => $this->payment_id,
                        'payment_url' => $payment_url,
                        'status' => 'waiting'
                    ])->save();
                }
            }
            return redirect()->route('payment.aamarpay.process', [
                'appointment' => $appointment->id,
                'transaction' => $transaction->id,
            ])->withInfo($message);
        }

        return [
            'status' => false,
            'message' => "Something is wrong try again!",
        ];
    }

    protected function getDataWith($additional = []) : array
    {
        return array_merge($this->data, $additional);
    }

    protected function processPayament($transaction, $appointment)
    {
        $rand = rand(111,999);
        $this->payment_id = "{$appointment->appointment_code}-TNX{$transaction->id}-{$rand}";
        return $this->client->prepare($this->getDataWith([
                        'transaction' => $this->payment_id,
                        'desc' => $transaction->description,
                        'opt_a' => $transaction->user_id,
                    ]))
                    ->redirectUrl(
                        route('payment.aamarpay.success', ['appointment' => $appointment->id, 'transaction' => $transaction->id]),
                        route('payment.aamarpay.cancel', ['appointment' => $appointment->id, 'transaction' => $transaction->id])
                    )
                    ->makePayment([
                        'opt_b' => $transaction->id,
                        'opt_c' => $appointment->id,
                    ]);
    }
}
