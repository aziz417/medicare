<?php

namespace App\Http\Controllers\API\V2;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResponse;
use App\Http\Controllers\Controller;

class DoctorScheduleController extends Controller
{
    public function index(User $doctor, Request $request)
    {
        $user = $request->user();
        $schedules = Schedule::when($user->isRole('doctor'), function($query)use($user){
            $query->where('doctor_id', $user->id);
        }, function($query)use($doctor){
            $query->where('doctor_id', $doctor->id);
        })->get()->groupBy('day');

        // dd($schedules);

        return new ApiResponse([
            'items' => $schedules->map(function($day, $key){
                return $day->map(function($item){
                    return $item->getPublicData();
                });
            })
        ]);
    }

    public function store(User $doctor, Request $request)
    {
        $validated = $request->validate([
            'day' => 'required',
            'items' => 'required|array',
            'items.*.duration' => 'required|numeric|min:15|max:60',
            'items.*.start_time' => 'required|distinct|date_format:H:i',
            'items.*.end_time' => 'required|date_format:H:i',
        ]);
        
        $schedules = [];
        foreach ($validated['items'] as $data) {
            $schedules[] = Schedule::create([
                'doctor_id' => $doctor->id ?? auth()->id(),
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'day' => $validated['day'],
                'duration' => $data['duration']
            ]);
        }
        if( $schedules ){
            return new ApiResponse([
                'items' => collect($schedules)->groupBy('day')->map(function($day, $key){
                return $day->map(function($item){
                    return $item->getPublicData();
                });
            })
            ], 201, 'Schedule created successfully!');
        }
        return new ApiResponse('Failed to create Schedule!', 422);
    }

    public function delete(User $doctor, Schedule $schedule, Request $request)
    {
        if( $schedule->delete() ){
            return new ApiResponse('Schedule deleted successfully!', 200);
        }
        return new ApiResponse('Failed to delete Schedule!', 400);
    }
}
