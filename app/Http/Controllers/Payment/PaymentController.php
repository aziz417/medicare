<?php

namespace App\Http\Controllers\Payment;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Services\PaymentGatewayManager;

class PaymentController extends Controller
{

    protected $gateway;
    public function __construct(PaymentGatewayManager $manager)
    {
        $this->gateway = $manager;
    }

    public function appointment(Appointment $appointment, Request $request)
    {
        if($appointment->isPending() && $appointment->appointment_fee > 0){
            if( optional($appointment->lastTransaction)->isConfirmed() ){
                $transaction = $appointment->lastTransaction;
                $appointment->fill([
                    'status' => $transaction->status
                ])->save();
                return redirect()->route('user.appointments.show', $appointment->id)->withInfo('Appointment confirmed!');
            }elseif( optional($appointment->lastTransaction)->isPending() || optional($appointment->lastTransaction)->isWaiting() ){
                $transaction = $appointment->lastTransaction;
                if( $transaction->status == 'due' ){
                    //
                }
            }else{
                $tax = (int) settings('payment_tax', 0);
                $transaction = $appointment->transactions()->create([
                    'user_id' => $appointment->user_id, 
                    'gateway' => $request->gateway ?? 'online', 
                    'method' => $request->method ?? 'portwallet', 
                    'amount' => $appointment->appointment_fee, 
                    'discount' => $appointment->discount, 
                    'discount_code' => $appointment->coupon_code, 
                    'final_amount' => (($appointment->appointment_fee - $appointment->discount) + $tax), 
                    'tax' => $tax, 
                    'type' => 'appointment',
                    'description' => "Appointment {$appointment->appointment_code} Payment Transaction!", 
                ]);
                $appointment->fill([
                    'transaction_id' => $transaction->id,
                    'status' => 'waiting',
                ])->save();
            }
            $paymentDriver = $transaction->gateway == 'online' ? $transaction->method : $transaction->gateway;
            $payment = $this->gateway->driver($paymentDriver)
                            ->setAmount($transaction->final_amount)
                            ->setCustomer([
                                'name' => $transaction->user->name,
                                'email' => $transaction->user->email,
                                'mobile' => $transaction->user->mobile,
                            ])
                            ->others([
                                'tax' => $transaction->tax,
                                'redirect_url' => route('payment.appointment.verify', $appointment->id)
                            ])
                            ->payment($transaction, $appointment);
             
            if( $payment instanceof Redirector || $payment instanceof RedirectResponse ){
                return $payment;
            }
            if( $payment['status'] ?? false ){
                return redirect()->route('user.appointments.show', $appointment->id)
                        ->withInfo($payment['message'] ?? 'Appointment Payment Successful!');
            }
            return redirect()->route('user.appointments.show', $appointment->id)
                        ->withInfo('Transaction created successfully, but could not proceed for payment!');;
        }elseif( $appointment->appointment_fee <= 0 ){
            $appointment->fill([
                'comment' => "Appointment auto approved due to zero payment!",
                'status' => 'approved',
            ])->save();
        }
        
        return redirect()->route('user.appointments.show', $appointment->id)->withInfo("Appointment already {$appointment->status}!");
    }
    
    public function verifyAppointmentPayment(Appointment $appointment, Request $request)
    {
        # code...
    }

    public function checkPayment($driver = "aamarpay", Request $request)
    {
        // dd($request->all());
        $tnxId = $request->tnx_id;
        if( empty($tnxId) ){
            return response()->json([
                'data' => "Invalid tnxId"
            ]);
        }
        $client = $this->gateway->driver($driver)->getClient();
        if( $driver === 'aamarpay' ){
            $response = $client->checkTnx($tnxId);
            return response()->json([
                'data' => $response
            ]);
        }
        return response()->json([
            'data' => "Invalid"
        ]);
    }
}
