<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorCharge extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['doctor_id', 'type', 'amount'];

    public function getAmount()
    {
        return $this->amount;
    }
}
