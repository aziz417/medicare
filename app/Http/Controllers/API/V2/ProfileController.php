<?php

namespace App\Http\Controllers\API\V2;

use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /**
     * Get the user profile details
     * @param  Request $request 
     * @return ApiResponse
     */
    public function index(Request $request)
    {
        return new ApiResponse($request->user()->getPublicData([
            'meta', 'status', 'submember_of', 'department', 'email_verified_at', 'mobile_verified_at'
        ]));
    }


    public function update(Request $request)
    {
        $user = $request->user();
        $userData = [];
        foreach ($request->all() as $key => $value) {
            if( str()->startsWith($key, 'user_') ){
                $userData[substr($key, strlen('user_'))] = $value;
            }
            if( str()->startsWith($key, 'charge_') ){
                $user->charges()->updateOrCreate(
                    ['type' => substr($key, strlen('charge_'))], 
                    ['amount' => $value]
                );
            }
            if( str()->startsWith($key, 'meta_') ){
                $saveKey = "user_".substr($key, strlen('meta_'));
                $user->setMeta($saveKey, $value);
            }
        }
        
        $user->fill($userData)->save();

        if( $request->hasFile('avatar') && $image = save_file($request->avatar, 'uploads/user') ){
            $user->fill([ 'picture' => $image ])->save();
        }
        if( $request->hasFile('signature') && $signature = save_file($request->signature, 'uploads/user') ){
            $user->setMeta('user_signature', $signature);
        }
        return new ApiResponse([
            'message' => 'Profile Updated Successfully!',
            // 'user' => $user->getPublicData([
            //     'meta', 'status', 'submember_of', 'department', 'email_verified_at', 'mobile_verified_at'
            // ])
        ]);
    }
}
