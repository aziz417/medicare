<?php 
namespace App\Services\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use App\Helpers\PayPalClient;
use App\Contracts\PaymentGateway;
use App\Helpers\PortWalletClient;
/**
 * PortWallet Pay
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class PortWallet implements PaymentGateway {
    /**
     * PortWallet Client Instance
     */
    protected $client;

    /**
     * Store the details
     */
    protected $data;

    public function __construct(){
        $this->client = new PortWalletClient();
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
            if( empty($transaction->payment_id) ){
                $result = $this->processPayament($transaction, $appointment);
                $response['paypal'] = $result ? $result : [];
                $link = $result['action']['url'] ?? $this->client->getProcessUrl($result['invoice_id'] ?? null, route('user.appointments.show', $appointment->id));
                
                $transaction->fill([
                    'response' => $response,
                    'payment_id' => $result['invoice_id'] ?? null,
                    'payment_url' => $link ?? null,
                    'status' => 'waiting'
                ])->save();
            }
            
            return redirect()->route('payment.portwallet.process', [
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
        $response = $this->client->makePayment($transaction, $appointment);
        if( $response && $response['result'] == 'success' ){
            return $response['data'] ?? [];
        }else{
            throw new \Exception( $response['error']['explanation'] ?? "Error Processing Request", 400);
        }
        return [];
    }
}
