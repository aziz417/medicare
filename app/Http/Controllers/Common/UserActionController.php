<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserActionController extends Controller
{
    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
        return response()->json([
            'status' => true,
            'message' => "Notifications marked as read!"
        ]);
    }
}
