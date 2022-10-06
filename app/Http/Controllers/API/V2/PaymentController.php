<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\AamarPayClient;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    protected $client;
    public function __construct(AamarPayClient $client)
    {
        $this->client = $client;
    }
    
    public function status(Request $request)
    {
        $appointment = Appointment::where('appointment_code', $request->appointment)->orWhere('id', $request->appointment)->first();
        $transaction = $appointment->lastTransaction;
        if( ! $appointment ){
            $transaction = Transaction::findOrFail($request->transaction);
        }
        if( !$transaction || !$appointment ){
            return abort(404);
        }

        return $appointment;
    }
}
