<?php

namespace App\Http\Controllers\API\V2;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobile' => ['required', 'string', 'unique:users', 'mobile'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'in:doctor,patient,user']
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => validateMobile($data['mobile']),
            'password' => Hash::make($data['password']),
            'role' => $data['type'] ?? 'patient'
        ]);
        $user->updateApiToken();

        event(new Registered($user));

        return new ApiResponse([
            'user' => $user->getPublicData('meta')
        ], 201, "User registered successfully, a email verification link is sent to your email!");
    }
}
