<?php

namespace App\Http\Controllers\API\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function doctors()
    {
        $users = User::whereRole('doctor')->get();
        return response()->json($users->map(function($item){
            return [
                'name' => $item->name,
                'email' => $item->email,
                'mobile' => $item->mobile,
                'picture' => asset($item->avatar()),
                'department' => $item->department->name ?? '',
                'meta' => collect($item->getSerializedMeta())->only(['user_designation', 'user_address', 'user_about', 'user_specialization', 'user_education_title', 'user_gender']),
            ];
        }), 200);
    }
}
