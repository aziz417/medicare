<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Create the Class Instance
     */
    public function __construct()
    {
        $this->middleware('role:master|admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = Department::when($request->search, function($query)use($request){
                    $query->whereLike('name', "%{$request->search}%");
                })->paginate(15);
        return view('admin.departments.list', compact('departments'));
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
            'description' => 'nullable|string'
        ]);
        $department = Department::create($validated);
        if( $department ){
            return back()->withSuccess("Department added successfully!");
        }
        return back()->withWarning("Failed to add Department!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        return view('admin.departments.view', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'description' => 'nullable|string'
        ]);
        $department->fill($validated);
        if( $department->save() ){
            return back()->withSuccess("Department updated successfully!");
        }
        return back()->withWarning("Failed to update Department!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        if( $department->delete() ){
            return back()->withSuccess("Department deleted successfully!");
        }
        return back()->withWarning("Failed to delete Department!");
    }
}
