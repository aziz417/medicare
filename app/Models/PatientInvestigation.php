<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientInvestigation extends Model
{
    /*
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'title', 'details', 'data', 'status'
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function generate($newData = [])
    {
        if( is_array($this->data) ){
            return array_merge($this->data, [$newData]);
        }
        return [$newData];
    }
}
