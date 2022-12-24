<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorScheduleOnOff extends Model
{
    protected $fillable = ['on_off', 'doctor_id'];
}
