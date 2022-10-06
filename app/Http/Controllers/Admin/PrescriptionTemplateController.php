<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrescriptionTemplate;
use Illuminate\Http\Request;

class PrescriptionTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $templates = PrescriptionTemplate::where('user_id', auth()->id())->orWhereNull('user_id')->paginate(20);
        return view('admin.prescriptions.templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.prescriptions.templates.create');
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
            'title' => 'required|string',
            'chief_complain' => 'required|string',
            'medicines' => 'required|array',
        ]);
        $medicines = $notes = [];
        foreach ($request->medicines as $key => $value) {
            $medicines[] = [
                'name' => $value, 
                'type' => $request->type[$key] ?? null, 
                'quantity' => $request->quantity[$key] ?? null, 
                'days' => $request->days[$key] ?? null, 
                'instruction' => $request->instruction[$key] ?? null
            ];
        }
        foreach ($request->diagnosis_title as $key => $value) {
            $notes[] = [
                'type' => "diagnosis",
                'title' => $value,
                'details' => $request->diagnosis_details[$key] ?? null
            ];
        }
        $prescription = PrescriptionTemplate::create([
            'user_id' => auth()->user()->isRole('doctor') ? auth()->id() : null,
            'title' => $request->title, 
            'chief_complain' => $request->chief_complain, 
            'advice' => $request->advice, 
            'investigations' => $notes, 
            'medicines' => $medicines
        ]);
        if( $prescription ){
            return back()->withSuccess("Prescription Created Successfully");
        }
        return back()->withInput()->withWarning("Something is wrong, Please try again!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PrescriptionTemplate  $prescriptionTemplate
     * @return \Illuminate\Http\Response
     */
    public function edit($prescription_template)
    {
        $template = PrescriptionTemplate::find($prescription_template);
        if( optional(auth()->user())->isRole('doctor') && $template->user_id != auth()->id() ){
            return back()->withWarning("Unable to edit another user template!");
        }
        return view('admin.prescriptions.templates.edit', compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PrescriptionTemplate  $prescriptionTemplate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $prescriptionTemplate)
    {
        $prescriptionTemplate = PrescriptionTemplate::find($prescriptionTemplate);
        if( optional(auth()->user())->isRole('doctor') && $prescriptionTemplate->user_id != auth()->id() ){
            return back()->withWarning("Unable to update another user template!");
        }
        $request->validate([
            'title' => 'required|string',
            'chief_complain' => 'required|string',
            'medicines' => 'required|array',
        ]);
        $medicines = $notes = [];
        foreach ($request->medicines as $key => $value) {
            $medicines[] = [
                'name' => $value, 
                'type' => $request->type[$key] ?? null, 
                'quantity' => $request->quantity[$key] ?? null, 
                'days' => $request->days[$key] ?? null, 
                'instruction' => $request->instruction[$key] ?? null
            ];
        }
        foreach ($request->diagnosis_title as $key => $value) {
            $notes[] = [
                'type' => "diagnosis",
                'title' => $value,
                'details' => $request->diagnosis_details[$key] ?? null
            ];
        }
        $prescriptionTemplate->fill([
            'title' => $request->title, 
            'chief_complain' => $request->chief_complain, 
            'advice' => $request->advice, 
            'investigations' => $notes, 
            'medicines' => $medicines
        ]);
        if( $prescriptionTemplate->save() ){
            return back()->withSuccess("Prescription Updated Successfully");
        }
        return back()->withInput()->withWarning("Something is wrong, Please try again!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PrescriptionTemplate  $prescriptionTemplate
     * @return \Illuminate\Http\Response
     */
    public function destroy($prescriptionTemplate)
    {
        $template = PrescriptionTemplate::find($prescriptionTemplate);
        if( optional(auth()->user())->isRole('doctor') && $template->user_id != auth()->id() ){
            return back()->withWarning("Unable to delete another user template!");
        }
        if( $template->delete() ){
            return back()->withSuccess("Prescription Deleted Successfully");
        }
        return back()->withInput()->withWarning("Something is wrong, Please try again!");
    }
}
