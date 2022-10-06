<?php

namespace App\Http\Controllers\API\V3;

use App\Models\Department;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ApiResponse;

class DoctorController extends Controller
{

    public function doctor_of_department(Request $request)
    {
        $doctors_of_all_department = Department::with('doctors')->get();
        return new ApiResponse([
            'items' => $doctors_of_all_department->map(function ($item) {
                return [
                    'department' => $item->getPublicData(),
                    'doctors' => $item->doctors->map(function ($review) {
                        return $review->getPublicData();
                    }),
                ];
            }),
        ]);
    }


    public function index(Request $request)
    {
        $start = $request->start ?: 0;
        $rows = $request->rows ?: -1;
        if ($rows != -1)
        {
            $doctors = User::whereRole('doctor')
                ->when($request->search, function ($query) use ($request) {
                    $query->whereLike(['name', 'email', 'department.name', 'meta.meta_value'], "%{$request->search}%");
                })
                ->when($request->department, function ($query) use ($request) {
                    $query->where('department_id', $request->department);
                })->withCount('reviews')->orderBy('reviews_count', 'desc')
                ->with('department', 'reviews')->skip($start)->take($rows)->get();
        }else{
            $doctors = User::whereRole('doctor')
                ->when($request->search, function ($query) use ($request) {
                    $query->whereLike(['name', 'email', 'department.name', 'meta.meta_value'], "%{$request->search}%");
                })
                ->when($request->department, function ($query) use ($request) {
                    $query->where('department_id', $request->department);
                })->withCount('reviews')->orderBy('reviews_count', 'desc')
                ->with('department', 'reviews')->get();
        }

        return new ApiResponse([
            'items' => $doctors->map(function ($item) {
                return [
                    'doctor_name' => $item->name,
                    'doctor_picture' => asset($item->avatar()),
                    'doctor_designation' => $item->getMeta('user_designation'),
                    'doctor_degree' => $item->getMeta('user_education_title') ?? old('meta_education_title'),
                    'doctor_specialization' => $item->getMeta('user_specialization') ?? old('meta_specialization'),
                    'doctor_about' => $item->getMeta('user_about', old('meta_about')),
                    'department_name' => $item->department->name,
                    'department_id' => $item->department->id,
                    'reviews_count' => $item->reviews->count(),
                    'rating' => (int) round($item->reviews->avg('rating'), 2),
                    'charge' => inCurrency($item->getCharge('report')->amount) .' - '. inCurrency($item->getCharge('booking')->amount),
                    'badges' => $item->badges,
                    'appointment_link' => route('user.doctors.booking', $item->id) ,
                ];
            })
        ]);
    }


    public function show(User $doctor, Request $request)
    {
        if (!$doctor->isRole('doctor')) {
            abort(404);
        }
        return new ApiResponse([

            'doctor_name' => $doctor->name,
            'doctor_picture' => asset($doctor->avatar()),
            'doctor_designation' => $doctor->getMeta('user_designation'),
            'doctor_degree' => $doctor->getMeta('user_education_title') ?? old('meta_education_title'),
            'doctor_specialization' => $doctor->getMeta('user_specialization') ?? old('meta_specialization'),
            'doctor_about' => $doctor->getMeta('user_about', old('meta_about')),
            'doctor_address' => $doctor->getMeta('user_address', '~') ,
            'department_name' => $doctor->department->name,
            'department_id' => $doctor->department->id,
            'reviews_count' => $doctor->reviews->count(),
            'rating' => (int) round($doctor->reviews->avg('rating'), 2),
            'charge' => inCurrency($doctor->getCharge('report')->amount) .' - '. inCurrency($doctor->getCharge('booking')->amount),
            'appointment_link' => route('user.doctors.booking', $doctor->id),
            'reviews' => $doctor->reviews->map(function ($item) {
                return $item->getPublicData();
            }),
            'badges' => $doctor->badges,
        ]);
    }

    private function mapCharges($charges)
    {
        $data = [];
        foreach ($charges as $item) {
            $data[$item->type] = $item->amount;
        }
        return $data;
    }
}
