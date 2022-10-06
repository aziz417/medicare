<?php

namespace App\Http\Controllers\Admin;

use App\Models\Badge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BadgeController extends Controller
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
    public function index(Request $request)
    {
        $badges = Badge::when($request->search, function($query)use($request){
                    $query->whereLike('name', "%{$request->search}%");
                })->paginate(15);
        return view('admin.badges.list', compact('badges'));
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
            'name' => 'required|string|max:200',
            'color' => 'required|string|max:200',
            'description' => 'nullable|string'
        ]);
        $badge = Badge::create($validated);
        if( $badge ){
            return back()->withSuccess("Badge added successfully!");
        }
        return back()->withWarning("Failed to add Badge!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Badge  $badge
     * @return \Illuminate\Http\Response
     */
    public function show(Badge $badge)
    {
        return view('admin.badges.view', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Badge  $badge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Badge $badge)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'color' => 'required|string|max:200',
            'description' => 'nullable|string'
        ]);
        $badge->fill($validated);
        if( $badge->save() ){
            return back()->withSuccess("Badge updated successfully!");
        }
        return back()->withWarning("Failed to update Badge!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Badge  $badge
     * @return \Illuminate\Http\Response
     */
    public function destroy(Badge $badge)
    {
        if( $badge->delete() ){
            return back()->withSuccess("Badge deleted successfully!");
        }
        return back()->withWarning("Failed to delete Badge!");
    }
}
