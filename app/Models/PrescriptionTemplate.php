<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionTemplate extends Model
{
    protected $fillable = ['user_id', 'title', 'chief_complain', 'advice', 'investigations', 'medicines' ];

    protected $casts = [
        'medicines' => 'array',
        'investigations' => 'array'
    ];

    protected $medicinesKey = [
        'name', 'type', 'quantity', 'days', 'instruction'
    ];

}
