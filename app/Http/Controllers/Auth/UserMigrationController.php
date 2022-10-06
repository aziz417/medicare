<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class UserMigrationController extends Controller
{
    protected $secret = "msar-medicsbd-relation-telemedicine";

    public function register(Request $request)
    {
        $token = $request->token;
        // $token = openssl_encrypt($password, "AES-128-ECB", $secret);
        $password = openssl_decrypt($token, "AES-128-ECB", $this->secret);
        $userdata = $request->all();
        $userdata['password'] = $password;
        if( $token && $password ){
            return view('auth.pre-register', compact('token', 'userdata'));
        }
        return view('register');
    }

    public function submit(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|mobile|unique:users',
            'password' => 'nullable|string|min:8'
        ]);
        $password = openssl_decrypt($request->token, "AES-128-ECB", $this->secret);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => validateMobile($data['mobile']),
            'password' => Hash::make($data['password'] ?? $password),
            'role' => 'patient'
        ]);
        if( $user ){
            event(new Registered($user));
            Auth::login($user);
            return redirect()->route('user.dashboard')->with("User created successfully!");
        }
        return back()->withWarning('Something is wrong, please try again!');
    }
}
