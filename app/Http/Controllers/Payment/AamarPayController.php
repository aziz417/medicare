<?php

namespace App\Http\Controllers\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\AamarPayClient;
use App\Http\Controllers\Controller;
use App\Events\Appointment\Confirmed;

class AamarPayController extends Controller
{
    protected $client;
    public function __construct(AamarPayClient $client)
    {
        $this->client = $client;
    }


    public function process(Request $request, Appointment $appointment, Transaction $transaction)
    {
        if( $transaction->payment_id && $transaction->isWaiting()){
            $details = $this->client->checkTnx($transaction->payment_id);
            $successArray = ['Successful'];
            if( $details && in_array($details['pay_status']??'', $successArray) && !$transaction->isConfirmed() ){
                $transaction->action('confirmed', [
                    'admin-comment' => "Transaction automatically approved by Payment Gateway (AamarPay)!",
                ]);
                $data = $transaction->response;
                $data['aamarpay'] = $details;
                $transaction->update([
                    // 'received_amount' => $request->received_amount ?? $transaction->final_amount
                    'response' => $data
                ]);
                try {
                    $appointment->doctor->getWallet()->add($transaction->received_amount);
                    $appointment->update([
                        'status' => 'approved'
                    ]);
                    event(new Confirmed($appointment));
                } catch (Exception $e) {
                    info("Failed to add balance to doctor wallet!\n{$e->getMessage()}");
                }

                return redirect()->route('user.appointments.show', $appointment->id)->withSuccess("Payment Successful!");
            }
            // rest of the action
            if( $transaction->payment_url ){
                return redirect($transaction->payment_url);
            }elseif( $transaction->response['payment_url'] ?? false ){
                return redirect($transaction->response['payment_url']);
            }
        }
        return redirect()->route('user.appointments.show', $appointment->id)->withError("Something is wrong, Try Again!");
    }

    public function success(Request $request, Appointment $appointment, Transaction $transaction)
    {
        $response = $request->all();
        if( ! $response['mer_txnid'] ?? true ){
            $response = $this->client->checkTnx($transaction->payment_id);
        }
        $successArray = ['Successful'];
        if( $response && in_array($response['pay_status'] ?? '', $successArray) && !$transaction->isConfirmed()){
            $data = $transaction->response ?? [];
            $data['aamarpay_success'] = $response ?? [];

            $transaction->action('confirmed', [
                'admin-comment' => "Transaction automatically approved by Payment Gateway (AamarPay)!",
            ]);
            $transaction->update([
                'received_amount' => $request->amount ?? $transaction->final_amount,
                'response' => $data
            ]);
            try {
                $appointment->doctor->getWallet()->add($transaction->received_amount);
                $appointment->update([
                    'status' => 'approved'
                ]);
                event(new Confirmed($appointment));
            } catch (Exception $e) {
                info("Failed to add balance to doctor wallet!\n{$e->getMessage()}");
            }

            return redirect()->route('user.appointments.show', $appointment->id)->withSuccess("Payment Successful!");
        }
        return redirect()->route('user.appointments.index')->withError("Payment confirmation failed, try again!");
    }

    public function cancel(Request $request, Appointment $appointment, Transaction $transaction)
    {
        $message = "Payment is canceled!";
        $response = $request->all();
        if( ! isset($response['mer_txnid']) ){
            $response = $this->client->checkTnx($transaction->payment_id);
        }
        $avaiable = ['Not-Available', 'Pending'];
        $data = $transaction->response ?? [];
        if( ($response['pay_status']??'') == 'Failed' ){
            $data['aamarpay_failed'] = $response ?? [];
        }
        $transaction->fill([
            'status' => in_array(($response['pay_status'] ?? ''), $avaiable) ? 'due' : 'canceled',
            'response' => $data
        ])->save();
        if( in_array(($response['pay_status'] ?? ''), $avaiable) ){
            $message = "Payment is not complete yet!";
        }
        // optional($transaction->appointment)->update([
        //     'status' => 'canceled'
        // ]);
        // dd($response, $transaction);
        return redirect()->route('user.appointments.show', $appointment->id)->withError($message);
    }
}
