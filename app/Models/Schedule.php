<?php

namespace App\Models;

use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['doctor_id', 'start_time', 'end_time', 'day', 'duration', 'status'];

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function getPublicData()
    {
        $slots = array_map(function($item){
            return _date($item)->format('Y-m-d H:i:s');
        }, $this->getTimeSlots()->toArray());
        return [
            'start_time' => $this->start_time, 
            'end_time' => $this->end_time, 
            'day' => $this->day, 
            'duration' => $this->duration, 
            'status' => $this->status,
            'slots' => $slots,
            'id' => $this->id
        ];
    }

    public function getTimeSlots()
    {
        $period = CarbonPeriod::create($this->start_time, $this->end_time)
        ->setDateInterval("PT{$this->duration}M");
        return $period;
    }
    
}
