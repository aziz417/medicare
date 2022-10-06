<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PatientInvestigation;

class PrescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub_members = User::where('submember_of', auth()->id())->select('id')->get();
        $data = array();
        foreach ($sub_members as $sub_member) {
            array_push($data, $sub_member->id);
        }
        array_push($data, auth()->id());
        $prescriptions = Prescription::whereIn('patient_id',$data)->active()->latest()->paginate(20);
        return view('user.prescriptions.list', compact('prescriptions'));
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
        return view('user.prescriptions.view', compact('prescription', 'investigations'));
    }

}
