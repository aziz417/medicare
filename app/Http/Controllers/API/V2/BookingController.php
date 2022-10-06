<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Discount;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Events\Appointment\Booked;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    public function submit(Request $request)
    {
        $request->validate([
            "scheduled_at" => "required|date_format:Y-m-d H:i",
            "schedule_id" => "required",
            "patient_id" => "required",
            "type" => "required|string",
            "patient_problem" => "required|string"
        ]);
        $discountApplicable = false;
        $user = $request->user();
        if( $request->has('coupon_code') ){
            $discount = Discount::where('code', $request->coupon_code)->first();
            $discountApplicable = $discount && $discount->check($user->id) ? $discount->amount : 0;
        }
        $schedule = Schedule::find($request->schedule_id);
        $doctor = $schedule->doctor;
        $schedule_at = _date($request->scheduled_at);
        $charge = $doctor->getCharge($request->type)->amount;
        if( ! $schedule ){
            return back()->withWarning('Invalid schedule!');
        }
        $appointment = Appointment::create([
            'user_id' => $user->id, 
            'patient_id' => $request->patient_id ?? $user->id, 
            'doctor_id' => $doctor->id, 
            'schedule_id' => $request->schedule_id, 
            'duration' => $schedule->duration, 
            'type' => $request->type, 
            'scheduled_at' => $schedule_at, 
            'scheduled_date' => $schedule_at->format('Y-m-d'), 
            'scheduled_time' => $schedule_at->format('H:i'), 
            'appointment_fee' => $charge, 
            'discount' => $discountApplicable, 
            'coupon_code' => $discountApplicable ? $request->coupon_code : null, 
            'patient_problem' => $request->patient_problem, 
        ]);
        if( $appointment ){
            if( $discountApplicable > 0 ){
                $discount->updateUses();
            }
            $notifier = $appointment->patient->isSubmember() ? $appointment->user : $appointment->patient;
            sendNotification($appointment->doctor, "You have a new Appointment {$appointment->appointment_code} booked by {$notifier->name}", [
                        'icon' => 'check', 'link' => route('admin.appointments.show', $appointment->id)
                    ]);
            event(new Booked($appointment));
            $defaultPayMethod = $request->payment_gateway=='manual' ? 'manual' : config('system.payment.default_method', 'portwallet');
            return new ApiResponse([
                'payment_url' => route('payment.appointment', [
                    'appointment' => $appointment->id,
                    'gateway' => $request->payment_gateway,
                    'method' => $request->payment_method ?? $defaultPayMethod,
                ]),
                'data' => [
                    'appointment' => $appointment->getAttributes(),
                    'patient' => $appointment->patient->getPublicData(),
                    'doctor' => $appointment->doctor->getPublicData(),
                    'schedule' => $appointment->schedule
                ]
            ]);
            
        }
        return new ApiResponse("Something is wrong, Please try again!");
    }
}
