<?php

namespace App\Http\Controllers\User;

use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $appointments = Appointment::where('user_id', auth()->id())
                        ->whereDate('scheduled_at', '>=', now())
                        ->latest('scheduled_at')->take(10)->get();
        return view('user.dashboard', compact('appointments'));
    }
}
