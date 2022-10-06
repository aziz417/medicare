<?php

namespace App\Http\Controllers\Common;

use PDF;
use App\Models\Prescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MPDF;
use App\Models\PatientInvestigation;
class PrescriptionController extends Controller
{
    public function download(Prescription $prescription, Request $request)
    {
        $investigations = PatientInvestigation::where('user_id', $prescription->patient_id)->latest()->get();
        $pdf = MPDF::loadView('common.prescriptions.download', compact('prescription', 'investigations'));
        if( $request->has('print') ){
            return view('common.prescriptions.download', compact('prescription'));
        }
        if( $request->has('stream') ){
            return $pdf->stream("Prescription-{$prescription->id}.pdf");
        }
        return $pdf->download("Prescription-{$prescription->id}.pdf");
    }
}
