<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $appointments = Appointment::when($user->isRole('doctor'), function($query){
            $query->where('doctor_id', auth()->id());
        })->with('patient', 'doctor')->latest()->take(15)->get();
        $dashboard = $this->getDashboardData();
        return view('admin.dashboard', compact('appointments', 'dashboard'));
    }

    public function accounts()
    {
        $accounts = [];
        return view('admin.transactions.balance', compact('accounts'));
    }

    private function getDashboardData()
    {
        $appointments = Appointment::count();
        $patients = User::whereIn('role', ['user', 'patient'])->count();
        $prescriptions = Prescription::count();
        if( auth()->user()->isRole('doctor') ){
            $earning = auth()->user()->getWallet()->total_earning ?? 0;
        }else{
            $earning = Appointment::whereIn('status', ['blocked', 'success', 'approved', 'confirmed'])
                    ->when(auth()->user()->isRole('doctor'), function($query){
                        $query->where('doctor_id', auth()->id());
                    })
                    ->sum(DB::raw('appointment_fee - discount'));
        }
        return compact('appointments', 'patients', 'prescriptions', 'earning');
    }
}
