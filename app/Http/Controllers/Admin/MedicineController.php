<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Create the Class Instance
     */
    public function __construct()
    {
        $this->middleware('role:master|admin|doctor');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicines = Medicine::paginate(100);
        return view('admin.medicines.list', compact('medicines'));
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
            'name' => 'required|array',
            'name.*' => 'required|string',
            'type' => 'required|array',
            'type.*' => 'required|string',
        ]);
        $medicines = [];
        foreach ($request->name as $key => $value) {
            $medicines[] = Medicine::create([
                'name' => $request->name[$key],
                'type' => $request->type[$key],
                'quantity' => $request->quantity[$key] ?? null,
                'category' => $request->category[$key] ?? null,
                'price' => $request->price[$key] ?? null,
            ]);
        }

        if( $medicines ){
            return back()->withSuccess("Medicine added successfully!");
        }
        return back()->withError("Failed to add Medicine!");
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'quantity' => 'nullable|string',
            'category' => 'nullable|string',
            'price' => 'nullable|string',
        ]);
        $medicine->fill([
            'name' => $request->name ?? $medicine->name,
            'type' => $request->type ?? $medicine->type,
            'quantity' => $request->quantity ?? $medicine->quantity,
            'category' => $request->category ?? $medicine->category,
            'price' => $request->price ?? $medicine->price,
        ]);

        if( $medicine->save() ){
            return back()->withSuccess("Medicine updated successfully!");
        }
        return back()->withError("Failed to update Medicine!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Medicine  $medicine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Medicine $medicine, Request $request)
    {
        if( $medicine->delete() ){
            return $request->expectsJson() ? response()->json([
                'status' => true,
                'message' => "Medicine deleted successfully!"
            ]) : back()->withSuccess('Medicine deleted successfully!');
        }
        return $request->expectsJson() ? response()->json([
                'status' => false,
                'message' => "Failed to delete Medicine!"
            ]) : back()->withSuccess('Failed to delete Medicine!');
    }
}
