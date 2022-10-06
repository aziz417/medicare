<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Discount;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Events\Appointment\Booked;
use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function booking(User $doctor)
    {
        if( !$doctor->isRole('doctor') ){abort(404);}
        $doctor_json = $doctor->only([
            'id', 'name', 'department', 'charges'
        ]);
        $schedules = Schedule::where('doctor_id', $doctor->id)->orderBy('start_time')->get()->groupBy('day');
        $dates = date_range(now(), 7);
        $appointments = Appointment::where('doctor_id', $doctor->id)
                        ->whereNotIn('status', ['completed', 'missed', 'declined'])
                        ->whereDate('scheduled_at', '>=', now())
                        ->pluck('scheduled_at')->toArray();
        $subMembers = User::where('submember_of', auth()->id())->get();

        return view('user.doctors.booking',
            compact('doctor', 'dates', 'schedules', 'appointments', 'doctor_json', 'subMembers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function bookingConfirm(Request $request, User $doctor)
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

        $schedule = Schedule::find($request->schedule_id);
        $schedule_at = _date($request->scheduled_at);
        $charge = $doctor->getCharge($request->type)->amount;
        if( $request->has('coupon_code') ){
            $discount = Discount::where('code', $request->coupon_code)->first();
            if ($discount && $discount->check($user->id)) {
                if ($discount->is_percentage == 1) {
                    $value = ($discount->amount / 100) * $charge;
                    $discountApplicable = $value > 0 ? $value : 0;
                }else{
                    $discountApplicable = $discount && $discount->check($user->id) ? $discount->amount : 0;
                }
            }
        }
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
            return redirect()->route('payment.appointment', [
                'appointment' => $appointment->id,
                'gateway' => $request->payment_gateway,
                'method' => $request->payment_method ?? $defaultPayMethod,
            ])->withSuccess('Appointment booked successfully, Please make payment to confirm!');
        }
        return back()->withError("Something is wrong, Please try again!");
    }

}
