<?php

namespace App\Http\Controllers\User;

use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $appointments = Appointment::where('user_id', auth()->id())
                        ->whereDate('scheduled_at', '>=', now())
                        ->latest('scheduled_at')->take(10)->get();

        $doctors = User::whereRole('doctor')
            ->when($request->search, function($query)use($request){
                $query->whereLike(['name', 'email', 'department.name', 'meta.meta_value'], "%{$request->search}%");
            })
            ->when($request->department, function($query)use($request){
                $query->where('department_id', $request->department);
            })
            // ->withCount('reviews')->orderBy('reviews_count', 'desc')
            ->withCount(['reviews as reviews_count' => function($query) {
                $query->select(DB::raw('coalesce(avg(rating),0)'));
            }])->orderByDesc('reviews_count')

            ->with('charges', 'department', 'reviews')->paginate(5);
        $departments = Department::all();

        return view('user.dashboard', compact('appointments', 'doctors', 'departments'));
    }
}
