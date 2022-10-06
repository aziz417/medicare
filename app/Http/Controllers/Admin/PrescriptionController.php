<?php

namespace App\Http\Controllers\Admin;

use App\Models\Advice;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PrescriptionTemplate;
use App\Http\Requests\PrescriptionRequest;
use App\Models\PatientInvestigation;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $prescriptions = Prescription::when(!$user->isAdmin(false) && $user->is_desk_doctor != 1, function($query)use($user){
            $query->where('doctor_id', $user->id);
        })->paginate(20);
        return view('admin.prescriptions.list', compact('prescriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $advices = Advice::all();
        $appointments = Appointment::whereDate('scheduled_at', '>', now()->subDays(30))->get(['id', 'appointment_code', 'user_id', 'status']);
        $patients = User::whereRole(['user', 'patient'])->get(['id', 'name']);
        $template = PrescriptionTemplate::find($request->template);
        $appointment = Appointment::find($request->appointment);
        $doctors = User::whereRole(['doctor'])->get(['id', 'name']);
        return view('admin.prescriptions.create', compact( 'advices','appointments', 'appointment', 'patients', 'template','doctors'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrescriptionRequest $request)
    {
        $prescription = Prescription::create([
            'appointment_id' => $request->appointment_id,
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id ?? auth()->id(),
            'chief_complain' => $request->chief_complain,
            'advice' => $request->advice,
            'investigations' => $request->investigations,
            'status' => $request->status
        ]);
        if( $prescription ){
            foreach ($request->medicines as $key => $value) {
                $prescription->medicines()->create([
                    'name' => $value,
                    'type' => $request->type[$key] ?? null,
                    'quantity' => $request->quantity[$key] ?? null,
                    'days' => $request->days[$key] ?? null,
                    'instruction' => $request->instruction[$key] ?? null
                ]);
            }
            foreach ($request->diagnosis_title as $key => $value) {
                $prescription->notes()->create([
                    'type' => "diagnosis",
                    'title' => $value,
                    'details' => $request->diagnosis_details[$key] ?? null
                ]);
            }
            sendNotification($prescription->patient, "A New Prescription is Generated against Appointment {$prescription->appointment->appointment_code}", [
                'icon' => 'prescription', 'link' => route('user.prescriptions.show', $prescription->id)
            ]);
            return redirect()->route('admin.prescriptions.show', $prescription->id)->withSuccess("Prescription Created Successfully");
        }
        return back()->withInput()->withWarning("Something is wrong, Please try again!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function show(Prescription $prescription)
    {
        $investigations = PatientInvestigation::where('user_id', $prescription->patient_id)->latest()->get();
        return view('admin.prescriptions.view', compact('prescription', 'investigations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function edit(Prescription $prescription)
    {
        $advices = Advice::all();
        $appointments = Appointment::whereDate('scheduled_at', '>', now()->subDays(30))->get(['id', 'appointment_code', 'user_id', 'status']);
        $patients = User::whereRole(['user', 'patient'])->get(['id', 'name']);

        return view('admin.prescriptions.edit', compact('advices','prescription', 'patients', 'appointments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function update(PrescriptionRequest $request, Prescription $prescription)
    {
        $prescription->fill([
            'chief_complain' => $request->chief_complain,
            'advice' => $request->advice,
            'investigations' => $request->investigations,
            'status' => $request->status
        ]);
        if( $prescription->save() ){
            foreach ($request->medicines as $key => $value) {
                $prescription->medicines()->create([
                    'name' => $value,
                    'type' => $request->type[$key] ?? null,
                    'quantity' => $request->quantity[$key] ?? null,
                    'days' => $request->days[$key] ?? null,
                    'instruction' => $request->instruction[$key] ?? null
                ]);
            }
            foreach ($request->diagnosis_title as $key => $value) {
                $prescription->notes()->create([
                    'type' => "diagnosis",
                    'title' => $value,
                    'details' => $request->diagnosis_details[$key] ?? null
                ]);
            }
            $prescription->medicines()->whereIn('id', arr()->wrap($request->medicine_ids))->get()
                ->each(function($item){ $item->delete(); });
            $prescription->notes()->whereIn('id', arr()->wrap($request->diagnosis_ids))->get()
                ->each(function($item){ $item->delete(); });

            return back()->withSuccess("Prescription Updated Successfully");
        }
        return back()->withInput()->withWarning("Something is wrong, Please try again!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prescription  $prescription
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prescription $prescription)
    {
        if( $prescription->delete() ){
            return back()->withSuccess("Prescription deleted successfully!");
        }
        return back()->withWarning("Failed to delete Prescription!");
    }
}
