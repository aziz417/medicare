<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionNote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['prescription_id', 'type', 'title', 'details'];
}
