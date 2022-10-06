<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;

class UserController extends Controller
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
        $users = User::when($request->search, function($query)use($request){
                    $query->whereLike(['name', 'email'], "%{$request->search}%");
                })
                ->when($request->role, function($query)use($request){
                    $query->whereIn('role', explode(',', $request->role));
                }, function($query){
                    $query->whereIn('role', ['master', 'admin']);
                })
                ->paginate(20);
        return view('admin.users.index', compact('users'));
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
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => validateMobile($validated['mobile']),
            'password' => bcrypt($validated['password']),
            'role' => $request->role
        ]);

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $user->fill([ 'picture' => $image ])->save();
        }
        if( $request->has('auto_verified') ){
            $user->forceFill([
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ]);
        }
        
        event(new Registered($user));
        
        return back()->withSuccess('User Created Successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$user->id}",
            'mobile' => "required|string|mobile|unique:users,mobile,{$user->id}",
        ]);
        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile' => validateMobile($validated['mobile']),
        ]);

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $user->fill([ 'picture' => $image ]);
        }
        if( $request->has('auto_verified') ){
            $user->forceFill([
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ]);
        }
        
        if( $user->save() ){
            return back()->withSuccess("User updated successfully!");
        }
        return back()->withWarning("Failed to update User!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if( $user->delete() ){
            return back()->withSuccess("User deleted successfully!");
        }
        return back()->withWarning("Failed to delete User!");
    }
}
