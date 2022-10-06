<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PatientInvestigation;

class UserInvestigationsController extends Controller
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
        $histories = PatientInvestigation::where('user_id', $request->user)->latest()->get()
            ->map(function($item){
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'details' => $item->details,
                    'data' => $item->data,
                    'update_link' => route('admin.investigations.update', $item->id),
                    'updated_at' => $item->updated_at->format('d M, Y'),
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
        // dd($request->all());
        $validated = $request->validate([
            'user_id' => 'required',
            'title' => 'required|string',
            'details' => 'required|string',
            'data_date' => 'required|array',
            'data_details' => 'required|array'
        ]);
        $data = [];
        foreach ($request->data_date as $key => $value) {
            $data[] = [
                'date' => _date($value, 'd M, Y'),
                'details' => $request->data_details[$key],
            ];
        }
        $investigation = PatientInvestigation::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'details' => $request->details,
            'data' => $data
        ]);
        if( $investigation ){
            return response()->json([
                'status' => true,
                'data' => [
                    'id' => $investigation->id,
                    'title' => $investigation->title,
                    'details' => $investigation->details,
                    'data' => $data,
                    'update_link' => route('admin.investigations.update', $investigation->id),
                    'updated_at' => $investigation->updated_at->format('d M, Y'),
                ]
            ], 201);
        }
        return response()->json([
            'status' => false,
            'message' => "Something went wrong!!"
        ]);
    }

    public function update(Request $request, PatientInvestigation $investigation)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'details' => 'required|string',
        ]);

        $data = $investigation->generate([
            'date' => _date($validated['date'], 'd M, Y'),
            'details' => $validated['details']
        ]);
        
        $investigation->update([
            'data' => $data
        ]);
        return response()->json([
            'status' => true,
            'id' => $investigation->id,
            'data' => $investigation->data
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PatientInvestigation  $investigation
     * @return \Illuminate\Http\Response
     */
    public function destroy(PatientInvestigation $investigation)
    {
        if( $investigation->delete() ){
            return response()->json([
                'status' => true,
            ], 200);
        }
        return response()->json([
            'status' => false,
        ]);
    }
}
