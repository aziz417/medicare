<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exceptions\ApiException;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        $user = User::where('email', $request->email)->orWhere('mobile', $request->email)->first();
        if( Hash::check($request->password, $user->password) ){
            return response()->json([
                'api_token' => $user->api_token,
                'expires_in' => 'never',
                'details' => $user->getPublicData(['meta', 'department'])
            ]);
        }
        throw new ApiException('Invalid Credentials!', 401);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|mobile|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        return new ApiResponse($validated);
    }

    public function me()
    {
        return auth()->user()->getPublicData('meta');
    }
}
