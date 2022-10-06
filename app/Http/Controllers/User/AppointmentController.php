<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AppointmentController extends Controller
{
    protected $availableActions = ['chat'];
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->id())
                        ->latest('scheduled_at')->paginate(20);
        return view('user.appointments.list', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departments = Department::all();
        $doctors = User::whereRole('doctor')->with('department')->get(['id', 'name', 'department_id']);
        $dates = date_range(now(), 7);
        return view('user.appointments.create', compact('doctors', 'departments', 'dates')); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor' => 'required'
        ]);
        return redirect()->route('user.doctors.booking', $request->doctor);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function show(Appointment $appointment)
    {
        return view('user.appointments.view', compact('appointment'));
    }
    public function showAction(Appointment $appointment, $action = null)
    {
        if( in_array($action, $this->availableActions) && $appointment->isConfirmed() ){
            return view('common.appointments.actions', compact('appointment', 'action'));
        }
        return view('user.appointments.view', compact('appointment'));
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
            'patient_problem' => 'required|string'
        ]);
        $appointment->fill($validated)->save();
        return back()->withSuccess("Appointment {$appointment->appointment_code} Updated!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        if($appointment->isCanceled()){
            return $appointment->delete() ? 
                    back()->withSuccess("Appointment deleted successfully!") : 
                    back()->withWarning('Failed to delete appointment!');
        }
        return back()->withSuccess("You can't delete this appointment!");
    }
}
