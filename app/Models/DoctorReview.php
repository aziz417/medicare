<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorReview extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'review_by', 'rating', 'details'
    ];

    public function getPublicData()
    {
        return [
            "id" => $this->id,
            "review_by" => $this->patient->getPublicData(),
            "rating" => $this->rating,
            "details" => $this->details,
            "reviewed_on" => $this->created_at,
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'review_by');
    }
}
