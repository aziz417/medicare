<?php

namespace App\Http\Controllers\API\V2;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Events\Auth\MobileVerified;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class VerifyController extends Controller
{
    public function verifyMobile(Request $request)
    {
        $request->validate([
            'mobile' => 'required|mobile',
        ]);
        $user = User::where('mobile', $request->mobile)->first();
        
        if( !$user ){
            return new ApiResponse("User not found!", 404);
        }

        if ($user->hasVerifiedMobile()) {
            return new ApiResponse("Already verified!", 200);
        }

        $request->validate([
            'code' => ['required', 'min:4', 'max:6', Rule::in([ $user->getSavedOtp() ?? 0])]
        ], [
            'code.in' => "Invalid verification code!"
        ]);

        if ( $user->markMobileAsVerified()) {
            event(new MobileVerified($user));
        }
        return new ApiResponse("Mobile verified successfully!", 200);
    }
    
    public function resendMobile(Request $request) 
    {
        $request->validate([
            'mobile' => 'required|mobile',
        ]);
        $user = User::where('mobile', $request->mobile)->first();

        if( !$user ){
            return new ApiResponse("User not found!", 404);
        }

        if ($user->hasVerifiedMobile()) {
            return new ApiResponse("Already verified!", 200);
        }

        $user->sendMobileVerificationNotification();

        return new ApiResponse("Code sent to your mobile!", 200);
    }

    public function resendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $user = User::where('email', $request->email)->first();

        if( !$user ){
            return new ApiResponse("User not found!", 404);
        }

        if ($user->hasVerifiedEmail()) {
            return new ApiResponse("Already verified!", 200);
        }

        $user->sendEmailVerificationNotification();

        return new ApiResponse("Email sent to your email address!", 200);
    }
}
