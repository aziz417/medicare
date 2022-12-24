<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Badge;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Create the Class Instance
     */
    public function __construct()
    {
        $this->middleware('role:master|admin|doctor')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $departments = Department::all();
        $badges = Badge::where('status', 'active')->get();
        $doctors = User::whereRole('doctor')
                ->when($request->search, function($query)use($request){
                    $query->whereLike(['name', 'email','department.name'], "%{$request->search}%");
                })
                ->with('charges', 'departments')->paginate(15);
        return view('admin.doctors.list', compact('doctors', 'departments', 'badges'));
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
            "name" => 'required|string|max:255',
            "email" => 'required|string|unique:users',
            "mobile" => 'nullable|string|unique:users',
            "designation" => 'required|string|required',
            "department_id" => 'required|array',
            "address" => 'nullable|string',
            "password" => 'required|string|confirmed',
            "charge_booking" => 'required|string|integer',
            "charge_reappoint" => 'required|string|integer',
            "charge_report" => 'required|string|integer',
            "avatar" => 'nullable|image|max:512',
            "badges" => 'nullable|array'
        ]);
        $doctor = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'department_id' => 2,
            'password' => bcrypt($request->password),
            'role' => 'doctor',
        ]);

        $doctor->departments()->attach($request->department_id);

        $doctor->setMeta('user_designation', $request->designation);
        $doctor->setMeta('user_address', $request->address);
        $charges = $doctor->charges()->createMany([
            ['type'=>'booking', 'amount'=>$request->charge_booking],
            ['type'=>'reappoint', 'amount'=>$request->charge_reappoint],
            ['type'=>'report', 'amount'=>$request->charge_report]
        ]);
        if( $request->badges && is_array($request->badges) ){
            $doctor->badges()->sync($request->badges);
        }

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $doctor->fill([ 'picture' => $image ])->save();
        }
        event(new Registered($doctor));
        return back()->withSuccess('Doctor Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function show(User $doctor)
    {
        return view('admin.doctors.view', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function edit(User $doctor)
    {
        $departments = Department::all();
        $badges = Badge::where('status', 'active')->get();
        return view('admin.doctors.edit', compact('doctor', 'departments', 'badges'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $doctor)
    {
        $request->validate([
            "name" => 'nullable|string|max:255',
            "email" => "nullable|string|unique:users,email,{$doctor->id}",
            "mobile" => "nullable|string|unique:users,mobile,{$doctor->id}",
            "designation" => 'nullable|string|required',
            "department_id" => 'nullable|array',
            "address" => 'nullable|string',
            "charge_booking" => 'nullable|string|integer',
            "charge_reappoint" => 'nullable|string|integer',
            "charge_report" => 'nullable',
            "avatar" => 'nullable|image|max:512',
            "status" => 'nullable|string',
            "badges" => 'nullable|array',
            "is_desk_doctor" => 'nullable'
        ]);

        $doctor->fill([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'department_id' => 2,
            'status' => $request->status ?? 'active',
            'is_desk_doctor' => $request->is_desk_doctor,
        ]);

        $doctor->setMeta('user_designation', $request->designation);
        $doctor->setMeta('user_address', $request->address);
        $doctor->setMeta('user_about', $request->about);
        $doctor->charges()->updateOrCreate( ['type'=>'booking'], ['amount'=>$request->charge_booking] );
        $doctor->charges()->updateOrCreate( ['type'=>'report'], ['amount'=>$request->charge_report] );
        $doctor->charges()->updateOrCreate( ['type'=>'reappoint'], ['amount'=>$request->charge_reappoint] );


        if( $request->badges && is_array($request->badges) ){
            $doctor->badges()->sync($request->badges);
        }

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $doctor->fill([ 'picture' => $image ]);
        }
        if( $doctor->save() ){
            $doctor->departments()->sync($request->department_id);

            return back()->withSuccess("Doctor updated successfully!");
        }
        return back()->withWarning("Failed to update Doctor!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $doctor
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $doctor)
    {
        if( $doctor->delete() ){
            return back()->withSuccess("Doctor deleted successfully!");
        }
        return back()->withWarning("Failed to delete Doctor!");
    }
}
