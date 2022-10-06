<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;

class PatientController extends Controller
{
    /**
     * Create the Class Instance
     */
    public function __construct()
    {
        $this->middleware('role:master|admin')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $patients = User::whereRole(['patient', 'user'])
                ->when($request->search, function($query)use($request){
                    $query->whereLike(['name', 'email'], "%{$request->search}%");
                })
                ->whereNull('submember_of')
                ->with('subMembers')
                ->paginate(15);
        return view('admin.patients.list', compact('patients'));
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'mobile' => 'required|string|mobile|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        $patient = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => validateMobile($validated['mobile']),
            'password' => bcrypt($validated['password']),
            'role' => 'patient'
        ]);
        $patient->setMeta('user_age', $request->user_age);
        $patient->setMeta('user_gender', $request->user_gender);
        $patient->setMeta('user_address', $request->user_address);

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $patient->fill([ 'picture' => $image ])->save();
        }
        event(new Registered($patient));
        
        return back()->withSuccess('Patient Created Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(User $patient)
    {
        return view('admin.patients.view', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(User $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $patient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$patient->id}",
            'mobile' => "required|string|mobile|unique:users,mobile,{$patient->id}",
        ]);
        $patient->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => validateMobile($validated['mobile']),
        ]);
        $patient->setMeta('user_age', $request->age);
        $patient->setMeta('user_gender', $request->gender);
        $patient->setMeta('user_address', $request->address);
        $patient->setMeta('user_blood_group', $request->blood_group);

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $patient->fill([ 'picture' => $image ]);
        }
        
        if( $patient->save() ){
            return back()->withSuccess("Patient updated successfully!");
        }
        return back()->withWarning("Failed to update Patient!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $patient)
    {
        if( $patient->delete() ){
            return back()->withSuccess("Patient deleted successfully!");
        }
        return back()->withWarning("Failed to delete Patient!");
    }
}
