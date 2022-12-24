<?php

namespace App\Http\Controllers\Admin;

use App\Models\DoctorScheduleOnOff;
use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if( auth()->user()->isRole('doctor') ){
            $schedules = Schedule::where('doctor_id', auth()->id())->get()->groupBy('day');
            return view('admin.schedules.list', compact('schedules'));
        }
        $doctors = User::whereRole('doctor')->get();
        $schedules = Schedule::where('status', 'active')
                ->when($request->doctor, function($query)use($request){
                    $query->where('doctor_id', $request->doctor);
                })
                ->when($request->day, function($query)use($request){
                    $query->where('day', $request->day);
                })
                ->paginate(20);
        return view('admin.schedules.index', compact('schedules', 'doctors'));
    }

    public function scheduleOnOff(Request $request){

        $preSchedule = DoctorScheduleOnOff::where('doctor_id', auth()->id())->first();
        if ($preSchedule){
            $preSchedule->update([
                'on_off' => $preSchedule->on_off == 1 ? 0 : 1,
            ]);
        }else{
            DoctorScheduleOnOff::create([
                'doctor_id' => auth()->user()->id,
                'on_off' => $request->on_off == true ? 1 : 0,
            ]);
        }
     return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'day' => 'required',
            'duration' => 'required|array|min:1',
            'duration.*' => 'required|numeric|min:15|max:60',
            'start_time' => 'required|array',
            'start_time.*' => 'required|distinct|date_format:H:i',
            'end_time' => 'required|array',
            'end_time.*' => 'required|date_format:H:i',
        ]);

        $schedules = [];
        foreach ($validated['duration'] as $key => $value) {
            $schedules[] = Schedule::create([
                'doctor_id' => $request->doctor_id ?? auth()->id(),
                'start_time' => $validated['start_time'][$key],
                'end_time' => $validated['end_time'][$key],
                'day' => is_array($validated['day']) ? $validated['day'][$key] : $validated['day'],
                'duration' => $validated['duration'][$key]
            ]);
        }
        if( $schedules ){
            return back()->withSuccess('Schedule created successfully!');
        }
        return back()->withSuccess('Failed to create Schedule!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule, Request $request)
    {
        if( $schedule->delete() ){
            return $request->expectsJson() ? response()->json([
                'status' => true,
                'message' => "Schedule deleted successfully!"
            ]) : back()->withSuccess('Schedule deleted successfully!');
        }
        return $request->expectsJson() ? response()->json([
                'status' => false,
                'message' => "Failed to delete Schedule!"
            ]) : back()->withSuccess('Failed to delete Schedule!');
    }
}
