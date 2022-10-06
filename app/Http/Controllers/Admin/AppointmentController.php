<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Appointment\Confirmed;

class AppointmentController extends Controller
{
    protected $availableActions = ['chat',];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::latest()
                    ->when(auth()->user()->isRole('doctor') && auth()->user()->is_desk_doctor == 0, function($query){
                        $query->where('doctor_id', auth()->id());
                    })
                    ->latest('scheduled_at')
                    ->with('patient', 'doctor', 'schedule')->paginate(20);
        $patients = User::where('status', 'active')->whereRole(['user', 'patient'])->get();
        $doctors = User::where('status', 'active')->whereRole('doctor')
                    ->when(auth()->user()->isRole('doctor'), function($query){
                        $query->where('id', auth()->id());
                    })
                    ->with('department', 'schedules', 'charges', 'doctorAppointments')->get()->append('slots');

        return view('admin.appointments.list', compact('appointments', 'doctors', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
          "user_id" => "required|numeric",
          "doctor_id" => "required|numeric",
          "schedule_id" => "required|numeric",
          "scheduled_date" => "required|date",
          "scheduled_time" => "required|date_format:H:i",
          "appointment_fee" => "required|numeric",
          "discount" => "nullable|numeric",
          "type" => "nullable|string",
          "patient_problem" => "required|string",
          "status" => "nullable|string",
        ]);
        $schedule = Schedule::find($request->schedule_id);

        $validated['duration'] = $schedule->duration;
        $validated['discount'] = $validated['discount'] ?? 0;
        $validated['scheduled_at'] = "{$validated['scheduled_date']} {$validated['scheduled_time']}";
        $appointment = Appointment::create($validated);
        if( $appointment ){
            return back()->withSuccess("Appointment added successfully!");
        }
        return back()->withWarning("Failed to add Appointment!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return view('admin.appointments.view', compact('appointment'));
    }
    public function showAction(Appointment $appointment, $action = null)
    {
        if( in_array($action, $this->availableActions) ){
            return view('common.appointments.actions', compact('appointment', 'action'));
        }
        return view('admin.appointments.view', compact('appointment'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
          "scheduled_date" => "required|date",
          "schedule_id" => "required|numeric",
          "scheduled_time" => "required|date_format:H:i",
          "appointment_fee" => "required|numeric",
          "status" => "nullable|string",
        ]);
        $oldStatus = $appointment->isConfirmed();
        $appointment->fill(array_filter($validated));


        if( $appointment->save() ){
            if( $appointment->isConfirmed() && !$oldStatus ){
                $name = $request->user()->name;
                $appointment->approveAppointment($request->comment);
                $notifier = $appointment->patient->isSubmember() ? $appointment->user : $appointment->patient;
                sendNotification($notifier, "Your Appointment {$appointment->appointment_code} confirmed by {$name}", [
                    'icon' => 'check', 'link' => route('user.appointments.show', $appointment->id)
                ]);
            }
            return back()->withSuccess("Appointment updated successfully!");
        }
        return back()->withWarning("Failed to update Appointment!");
    }
    public function forceUpdate(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'action' => 'required|sometimes|in:success,declined',
            'comment' => 'required|sometimes|string',
        ]);
        $data = array_filter($validated);
        if( count($data) ){
            if( isset($data['action']) ){
                $data['status'] = $data['action'];
                unset($data['action']);
            }
            $appointment->fill($data)->save();
            if( $request->action === 'success' ){
                $appointment->approveAppointment($request->comment);
                event(new Confirmed($appointment));
            }
        }
        if( $request->has('is_completed') ){
            $appointment->fill([
                'is_completed' => (bool) $request->is_completed
            ])->save();
        }

        return back()->withSuccess("Appointment updated successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        if( $appointment->delete() ){
            return back()->withSuccess("Appointment deleted successfully!");
        }
        return back()->withWarning("Failed to delete Appointment!");
    }
}
