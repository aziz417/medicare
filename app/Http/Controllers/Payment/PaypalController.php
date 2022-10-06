<?php

namespace App\Http\Controllers\Payment;

use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\PayPalClient;
use App\Http\Controllers\Controller;
use App\Events\Appointment\Confirmed;

class PaypalController extends Controller
{
    protected $client;
    public function __construct(PayPalClient $client)
    {
        $this->client = $client;
    }

    public function process(Request $request, Appointment $appointment, Transaction $transaction)
    {
        if( $transaction->payment_id ){
            $details = $this->client->getDetails($transaction->payment_id);
            $successArray = ['COMPLETED'];
            if( $details && in_array($details->status??'', $successArray) && !$transaction->isConfirmed() ){
                $transaction->action('confirmed', [
                    'admin-comment' => "Transaction automatically approved by Payment Gateway (Paypal)!",
                ]);
                $data = $transaction->response;
                $data['paypal'] = $details;
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
            }elseif( $transaction->payment_id ){
                $base = config('services.payment.paypal.sandbox', false) ? "www.sandbox.paypal.com" : "www.paypal.com";
                $url = "https://{$base}/checkoutnow?token={$transaction->payment_id}";
                return redirect($url);
            }
        }

        return back()->withError("Something is wrong, Try Again!");
    }

    public function success(Request $request, Appointment $appointment, Transaction $transaction)
    {
        $response = $this->client->callback($request->token);
        $successArray = ['COMPLETED'];
        if( $response && in_array($response->status??'', $successArray) ){
            $data = $transaction->response ?? [];
            $data['paypal'] = $response ?? [];

            $transaction->action(in_array($response->status, $successArray) ? 'confirmed' : 'failed', [
                'admin-comment' => "Transaction automatically approved by Payment Gateway (Paypal)!",
            ]);
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
        }
        return redirect()->route('user.appointments.index')->withError("Payment confirmation failed, try again!");
    }

    public function cancel(Request $request, Appointment $appointment, Transaction $transaction)
    {
        $transaction->fill([
            'status' => 'canceled'
        ])->save();
        optional($transaction->appointment)->update([
            'status' => 'canceled'
        ]);
        return redirect()->route('user.appointments.index')->withError("Payment canceled!");
    }
}
