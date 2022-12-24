<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['investigation_title', 'group_desc', 'icd_code', 'who_full_desc', 'appointment_id', 'patient_id', 'doctor_id', 'chief_complain', 'advice', 'investigations', 'status'];

    /**
     * The attributes that should be autoload when a user called.
     *
     * @var array
     */
    protected $with = [ 'medicines', 'notes', 'appointment', 'doctor', 'patient' ];

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['active', 'show']);
    }

    public function medicines()
    {
        return $this->hasMany(PrescriptionMedicine::class, 'prescription_id');
    }
    public function notes()
    {
        return $this->hasMany(PrescriptionNote::class, 'prescription_id');
    }
    public function getNotes($type = 'general')
    {
        return $this->notes->where('type', $type)->all() ?? [];
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }
}
