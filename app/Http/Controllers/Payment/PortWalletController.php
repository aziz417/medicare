<?php

namespace App\Http\Controllers\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\PortWalletClient;
use App\Http\Controllers\Controller;
use App\Events\Appointment\Confirmed;

class PortWalletController extends Controller
{
    protected $client;
    public function __construct(PortWalletClient $client)
    {
        $this->client = $client;
    }

    public function process(Request $request, Appointment $appointment, Transaction $transaction)
    {
        if( $transaction->payment_id ){
            $details = $this->client->getInvoice($transaction->payment_id);
            $successArray = ['ACCEPTED'];
            if( $details && in_array($details['data']['order']['status'] ?? '', $successArray) && !$transaction->isConfirmed() ){
                $transaction->action('confirmed', [
                    'admin-comment' => "Transaction automatically approved by Payment Gateway (PortWallet)!",
                ]);
                $data = $transaction->response;
                $data['portwallet'] = $details;
                $transaction->update([
                    // 'received_amount' => $request->received_amount ?? $transaction->final_amount
                    'response' => $data
                ]);
                try {
                    $appointment->doctor->getWallet()->add($transaction->received_amount);
                    $appointment->update([
                        'status' => 'approved'
                    ]);
                } catch (Exception $e) {
                    info("Failed to add balance to doctor wallet!\n{$e->getMessage()}");
                }

                return redirect()->route('user.appointments.show', $appointment->id)->withSuccess("Payment Successful!");
            }
            // rest of the action
            if( $transaction->payment_url ){
                return redirect($transaction->payment_url);
            }elseif( $transaction->payment_id ){
                $url = $this->client->getProcessUrl($transaction->payment_id);
                return redirect($url);
            }
        }

        return back()->withError("Something is wrong, Try Again!");
    }

    public function callback(Request $request, Appointment $appointment, Transaction $transaction)
    {
        $response = $this->client->getInvoice($request->invoice ?? $transaction->payment_id);
        $successArray = ['ACCEPTED'];
        $message = "Appointment {$appointment->appointment_code} Payment confirmation failed, try again!";
        $status = $response['data']['order']['status'] ?? $request->status ?? '';
        if( $response && in_array($status, $successArray) && !$transaction->isConfirmed() ){
            $data = $transaction->response ?? [];
            $data['portwallet'] = $response ?? [];

            $transaction->action(in_array($status, $successArray) ? 'confirmed' : 'failed', [
                'admin-comment' => "Transaction automatically approved by Payment Gateway (PortWallet)!",
            ], ['response' => $data]);
            // $transaction->update([
            //     'received_amount' => $request->received_amount ?? $transaction->final_amount
            // ]);
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
        }elseif( in_array($status, ['REJECTED']) && !$transaction->isConfirmed() ){
            $transaction->action('rejected', [
                'admin-comment' => "Transaction automatically rejected by Payment Gateway (PortWallet)!",
            ], ['response' => $data]);
            $message = "Transaction rejected by Payment Gateway (PortWallet)!";
        }elseif( in_array($status, ['CANCELLED']) && !$transaction->isConfirmed() ){
            $transaction->action('canceled', [
                'admin-comment' => "Transaction automatically canceled by Payment Gateway (PortWallet)!",
            ], ['response' => $data]);
            $message = "Transaction canceled by Payment Gateway (PortWallet)!";
        }
        if( $transaction->isConfirmed() && $appointment->isConfirmed() ){
            $message = "Appointment {$appointment->appointment_code} already approved!";
        }
        return redirect()->route('user.appointments.index')->withError($message);
    }

    public function ipnCallback(Request $request, Appointment $appointment, Transaction $transaction)
    {
        $logData = ['request' => $request->all()];
        $response = $this->client->verifyIPN($transaction->payment_id, $transaction->final_amount);
        $successArray = ['ACCEPTED'];
        $status = $response['data']['order']['status'] ?? $request->status ?? '';
        $logData['ipn_response'] = $response;

        if( $response && in_array($status, $successArray) && !$transaction->isConfirmed() ){
            $data = $transaction->response ?? [];
            $data['portwallet'] = $response ?? [];

            $transaction->action(in_array($status, $successArray) ? 'confirmed' : 'failed', [
                'admin-comment' => "Transaction automatically approved by Payment Gateway (PortWallet)!",
            ]);
            try {
                $appointment->doctor->getWallet()->add($transaction->received_amount);
                $appointment->update([
                    'status' => 'approved'
                ], ['response' => $data]);
                event(new Confirmed($appointment));
            } catch (Exception $e) {
                info("Failed to add balance to doctor wallet!\n{$e->getMessage()}");
                return false;
            }
        }elseif( in_array($status, ['REJECTED']) ){
            $transaction->action('rejected', [
                'admin-comment' => "Transaction automatically rejected by Payment Gateway (PortWallet)!",
            ], ['response' => $data]);
        }elseif( in_array($status, ['CANCELLED']) ){
            $transaction->action('canceled', [
                'admin-comment' => "Transaction automatically canceled by Payment Gateway (PortWallet)!",
            ], ['response' => $data]);
        }
        info(json_encode($logData));
        return true;
    }
}
