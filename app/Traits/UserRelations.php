<?php 
namespace App\Traits;

use App\Models\User;
use App\Models\Badge;
use App\Models\Wallet;
use App\Models\Schedule;
use App\Models\UserMeta;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\Transaction;
use App\Models\DoctorCharge;
use App\Models\DoctorReview;
use App\Models\PatientHistory;

/**
 * UserRelations
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
trait UserRelations
{
    // Relationship Helper methods trait
    use UserRelationsHelper;

    /**
     * Relation with Users Meta
     * @return mixed
     */
    public function meta()
    {
        return $this->hasMany(UserMeta::class, 'user_id');
    }

    /**
     * Relation with User SubMembers
     * @return mixed
     */
    public function subMembers()
    {
        return $this->hasMany(User::class, 'submember_of');
    }
    /**
     * Relation with SubMember Parent
     * @return mixed
     */
    public function member()
    {
        return $this->belongsTo(User::class, 'submember_of');
    }

    /**
     * Relation with Doctor Charge
     * @return mixed
     */
    public function charges()
    {
        return $this->hasMany(DoctorCharge::class, 'doctor_id');
    }

    /**
     * Relation with Doctor Prescription Template
     * @return mixed
     */
    public function templates()
    {
        return $this->hasMany(PrescriptionTemplate::class, 'user_id');
    }

    /**
     * Relation with Doctor Review
     * @return mixed
     */
    public function reviews()
    {
        return $this->hasMany(DoctorReview::class, 'user_id')->latest('rating')->latest('created_at')->limit(10);
    }

    /**
     * Relation with Patient History
     * @return mixed
     */
    public function history()
    {
        return $this->hasMany(PatientHistory::class, 'user_id');
    }

    /**
     * Relation with Doctor Department
     * @return mixed
     */
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Relation with User Wallet
     * @return mixed
     */
    public function wallet()
    {
        return $this->belongsTo(Wallet::class, 'id', 'user_id');
    }

    /**
     * Relation with Doctor Schedule
     * @return mixed
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id')->orderBy('start_time');
    }

    /**
     * Relation with User/Patient Appointments
     * @return mixed
     */
    public function userAppointments()
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }
    /**
     * Relation with Doctor Appointments
     * @return mixed
     */
    public function doctorAppointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Relation with User Transaction
     * @return mixed
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Relation with all Badges
     * @return mixed
     */
    public function badges()
    {
        return $this->belongsToMany(
                    Badge::class, 
                    'user_badges', 
                    'user_id', 
                    'badge_id'
                )->latest();
    }
}