<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ManualPaymentController extends Controller
{
    /**
     * Manual Payment Verification Details
     * @param  Appointment $appointment [description]
     * @param  Transaction $transaction [description]
     * @return view
     */
    public function show(Appointment $appointment, Transaction $transaction)
    {
        return view('payments.manual', compact('appointment', 'transaction'));
    }

    /**
     * Process payment verification
     * @param  Appointment $appointment [description]
     * @param  Transaction $transaction [description]
     * @return mixed
     */
    public function process(Appointment $appointment, Transaction $transaction, Request $request)
    {
        $request->validate([
            'account_number' => 'required|string',
            'transaction_id' => 'required|string',
            'method' => 'required|string',
            'amount' => 'required|integer',
            'comment' => 'nullable|string',
        ]);
        $saved = $transaction->fill([
            'received_amount' => $request->amount,
            'method' => $request->method,
            'response' => [
                'message' => 'User submitted payment details!',
                'data' => $request->only(['account_number', 'transaction_id', 'method', 'comment'])
            ],
            'status' => 'submitted'
        ])->save();
        $admins = User::whereRole(['master', 'admin'])->get();
        try {
            foreach($admins as $notifier){
                sendNotification($notifier, "A new Appointment ({$appointment->appointment_code}) booked by {$appointment->user->name}, and the user choose manual option for payment!", [
                    'icon' => 'check', 'link' => route('admin.appointments.show', $appointment->id)
                ]);
            }
        } catch (\Exception $e) {
            debug($e);
        }
        if($saved){
            return redirect()->route('user.appointments.index')->withInfo('Thanks for your confirmation, We will review your details and take action against this appointment.');
        }
        return back()->withWarning('Something is wrong, Try again!');
    }
}
