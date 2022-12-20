<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PatientHistory;
use Illuminate\Http\Request;

class UserHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if( ! $request->user ){
            return response()->json([
                'status' => false,
                'message' => "User id is required!"
        ]);
        }
        $histories = PatientHistory::where('user_id', $request->user)->latest()->get()
            ->map(function($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'details' => $item->details,
//                    'report' => $item->report,
                    'created_at' => $item->created_at->format('d M, Y'),
                ];
            });
        return response()->json([
            'status' => true,
            'data' => $histories
        ]);
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
            'user_id' => 'required',
            'title' => 'required|string',
            'details' => 'required|string',
//            'report' => 'required|string',
        ]);
        $history = PatientHistory::create($validated);
        if( $history ){
            return response()->json([
                'status' => true,
                'data' => $history
            ], 201);
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
        if( $history->delete() ){
            return response()->json([
                'status' => true,
            ], 200);
        }
        return response()->json([
            'status' => false,
        ]);
    }

    public function edit(Request $request)
    {
        $history = PatientHistory::where('id', $request->id)->first();
        return response()->json([
            'status' => true,
            'data' => $history
        ]);
    }
    public function update(Request $request)
    {
        $history = PatientHistory::where('id', $request->user_id)->first();
        $request->validate([
            'title' => 'required|string',
            'details' => 'required|string',
//            'report' => 'required|string',
        ]);
        $history->update([
            'title' => $request->title,
            'details' => $request->details,
//            'report' => $request->report,
        ]);

            return response()->json([
                'status' => true,
                'data' => $history,
                'message' => 'Update successfully',
            ], 201);

//        return response()->json([
//            'status' => false,
//            'message' => "Something is wrong!!"
//        ]);
    }
}
