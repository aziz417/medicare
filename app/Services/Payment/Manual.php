<?php 
namespace App\Services\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use App\Contracts\PaymentGateway;

/**
 * Manual
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class Manual implements PaymentGateway
{

    /**
     * Store the transaction
     */
    protected $transaction;
    /**
     * Store the details
     */
    protected $data;

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

            $transaction->fill([
                'response' => $response,
                'status' => 'waiting'
            ])->save();
            return redirect()->route('payment.manual', [
                'appointment' => $appointment->id,
                'transaction' => $transaction->id,
            ])->withInfo($message);
        }

        return [
            'status' => false,
            'message' => "Something is wrong try again!",
        ];
    }
}