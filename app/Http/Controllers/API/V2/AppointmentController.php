<?php

namespace App\Http\Controllers\API\V2;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = Appointment::latest()
                    ->when($user->isRole('doctor'), function($query) use ($user){
                        $query->where('doctor_id', $user->id);
                    })
                    ->when($user->isRole(['patient', 'user']), function($query) use ($user){
                        $query->where('user_id', $user->id);
                    })
                    ->latest('scheduled_at')
                    ->with('patient', 'doctor', 'schedule')->paginate(15);

        return new ApiResponse([
            'items' => $appointments->map(function($item){
                return [
                    'appointment' => $item->getAttributes(),
                    'patient' => $item->patient->getPublicData(),
                    'doctor' => $item->doctor->getPublicData(),
                    'schedule' => $item->schedule
                ];
            }),
            'next' => $appointments->nextPageUrl()
        ]);
    }

    public function show(Appointment $appointment, Request $request)
    {
        return new ApiResponse([
            'appointment' => $appointment->getAttributes(),
            'patient' => $appointment->patient->getPublicData(),
            'doctor' => $appointment->doctor->getPublicData(),
            'schedule' => $appointment->schedule
        ]);
    }

    public function delete(Appointment $appointment, Request $request)
    {
        if( $appointment->isPending() || $appointment->isCanceled() ){
            $appointment->delete();
            return new ApiResponse("Appointment deleted successful!");
        }
        return new ApiResponse("You are not able to delete this appointment!", 400);
    }
}
