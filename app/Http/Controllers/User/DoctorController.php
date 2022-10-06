<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Models\Schedule;
use App\Models\Department;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $doctors = User::whereRole('doctor')
                ->when($request->search, function($query)use($request){
                    $query->whereLike(['name', 'email', 'department.name', 'meta.meta_value'], "%{$request->search}%");
                })
                ->when($request->department, function($query)use($request){
                    $query->where('department_id', $request->department);
                })
                // ->withCount('reviews')->orderBy('reviews_count', 'desc')
                ->withCount(['reviews as reviews_count' => function($query) {
                    $query->select(DB::raw('coalesce(avg(rating),0)'));
                }])->orderByDesc('reviews_count')

                ->with('charges', 'department', 'reviews')->paginate(16);
        $departments = Department::all();
        return view('user.doctors.list', compact('doctors', 'departments'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(User $doctor)
    {
        return view('user.doctors.view', compact('doctor'));
    }

    public function saveReview(User $doctor, Request $request)
    {
        $request->validate([
            'rating' => 'required',
            'details' => 'nullable|string',
        ]);
        $review = $doctor->reviews()->create([
            'review_by' => auth()->id(),
            'rating' => (int) $request->rating,
            'details' => $request->details,
        ]);
        if( !$review ){
            if( $request->expectsJson() ){
                return response()->json([
                    'status' => false,
                    'message' => 'Something is wrong!'
                ], 201);
            }
            return back()->withWarning('Something is wrong!');
        }
        if( $request->expectsJson() ){
            return response()->json([
                'status' => true,
                'data' => $review
            ], 201);
        }
        return back()->withSuccess('Review submitted successfully!');
    }

}
