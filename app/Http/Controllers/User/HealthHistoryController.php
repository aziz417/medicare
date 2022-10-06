<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PatientHistory;
use Illuminate\Http\Request;

class HealthHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $history = PatientHistory::where('user_id', auth()->id())->orderBy('date', 'DESC')->latest()->paginate(15);
        return view('user.history', compact('history'));
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
            'title' => 'required|string',
            'details' => 'nullable|string',
            'date' => 'nullable|date',
        ]);
        $validated['user_id'] = auth()->id();
        $history = PatientHistory::create($validated);
        if( $history ){
            return back()->withSuccess("History added successfully!");
        }
        return back()->withSuccess("History adding failed!");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PatientHistory  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PatientHistory $history)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'details' => 'required|string',
        ]);
        $history->fill($validated);
        if( $history->save() ){
            if(! $request->expectsJson() ){
                return back()->withSuccess('Updated successfully!');
            }
            return response()->json([
                'status' => true,
                'data' => $history
            ], 201);
        }
        if(! $request->expectsJson() ){
            return back()->withWarning('Updating failed!');
        }
        return response()->json([
            'status' => false,
            'message' => "Something is wrong!!"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PatientHistory  $history
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatientHistory $history)
    {
        if($history->delete()){
            return back()->withSuccess("History deleted successfully!");
        }
        return back()->withSuccess("History deleting failed!");
    }
}
