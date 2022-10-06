<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['appointment_code', 'user_id', 'patient_id', 'doctor_id', 'schedule_id', 'duration', 'transaction_id', 'type', 'scheduled_at', 'scheduled_date', 'scheduled_time', 'appointment_fee', 'discount', 'coupon_code', 'patient_problem', 'comment', 'is_completed', 'notified', 'status'];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleted(function($item){
            $item->messages()->delete();
            $item->transactions()->delete();
            $item->prescriptions()->delete();
        });
        static::creating(function ($item) {
            if( empty($item->patient_id) ){
                $item->fill(['patient_id' => $item->user_id]);
            }
        });
        static::created(function ($item) {
            $item->fill([
                'appointment_code' => "#{$item->id}{$item->doctor_id}{$item->user_id}"
            ])->save();
        });
    }

    public function getRemainingWaitingTime($in = 'seconds'){
        $date = $this->scheduled_at->subMinutes(5);
        if( $date->greaterThan(now()) ){
            if($in=='seconds') return $date->diffInSeconds(now());
            if($in=='minutes') return $date->diffInMinutes(now());
            if($in=='hours') return $date->diffInHours(now());
        }
        return 'expired';
    }
    public function timeIsApeared(){
        $first = _date($this->scheduled_at)->subMinute(5);
        $second = _date($this->scheduled_at)->addMinute($this->duration + 5);
        return now()->between($first, $second);
    }
    public function isExpired($data = false)
    {
        $scheduled_at = _date($this->scheduled_at);
        $expired = now()->greaterThan($scheduled_at);
        if( $data === 'message' ){
            if( $this->timeIsApeared() ){
                return "Running";
            }
            return $expired ? 'Passed' : 'Upcoming';
        }elseif( $data === 'class' ){
            if( $this->timeIsApeared() ){
                return "text-primary";
            }
            return $expired ? 'text-danger' : 'text-info';
        }else{
            return $expired;
        }
        return $expired;
    }

    public function isPending()
    {
        return in_array($this->status, ['pending', 'waiting', 'submitted']);
    }
    public function isConfirmed()
    {
        return in_array($this->status, ['blocked', 'success', 'approved', 'confirmed']);
    }
    public function isCompleted()
    {
        return $this->is_completed || in_array($this->status, ['completed', 'closed']);
    }
    public function isCanceled()
    {
        return in_array($this->status, ['missed', 'declined', 'canceled', 'rejected']);
    }

    public function getPayableAmount($withCurrency = false)
    {
        $amount =  $this->appointment_fee - $this->discount;
        return $withCurrency ? inCurrency($amount) : $amount;
    }
    
    public function getStatusMessage()
    {
        switch ($this->status) {
            case 'blocked':
            case 'success':
                return "Appointment manually approved by Admin";
                break;
            case 'approved':
            case 'confirmed':
                return "Appointment Confirmed";
                break;
            case 'pending':
                return "Appointment created, but pending.";
                break;
            case 'waiting':
                return "Waiting for payment confirmation.";
                break;
            case 'completed':
            case 'closed':
                return "Appointment completed or closed!";
                break;
            case 'missed':
            case 'declined':
            case 'canceled':
            case 'rejected':
                return "Appointment canceled due to {$this->comment}";
                break;
            default:
                return "Appointment hold on {$this->status}";
                break;
        }
    }

    /**
     * Relation with Message Model
     * @return mixed
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'room_id');
    }
    /**
     * Relation with User Model
     * @return mixed
     */
    public function patient()
    {
        if( !empty($this->patient_id) ){
            return $this->belongsTo(User::class, 'patient_id');
        }
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    /**
     * Relation with User Model
     * @return mixed
     */
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    /**
     * Relation with Schedule Model
     * @return mixed
     */
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Relation with Last Transaction Model
     * @return mixed
     */
    public function lastTransaction()
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', );
    }
    /**
     * Relation with all Transactions Model
     * @return mixed
     */
    public function transactions()
    {
        return $this->belongsToMany(
                    Transaction::class, 
                    'appointments_transactions', 
                    'transaction_id', 
                    'appointment_id'
                )->latest();
    }
    
    /**
     * Relation with Prescription Model
     * @return mixed
     */
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'appointment_id')->latest();
    }

    public function userCanJoinRoom($userId)
    {
        if( ! $this->isConfirmed() ){
            return false;
        }
        return $this->doctor_id == $userId || $this->user_id == $userId;
    }

    // Call when try to approved manually
    public function approveAppointment($comment = 'Transaction Approved!')
    {
        if( $this->lastTransaction && !$this->lastTransaction->isConfirmed() ){
            $amount = $this->lastTransaction->received_amount ?? ($this->appointment_fee - $this->discount);
            $this->lastTransaction->action('approved', [
                'admin-comment' => $comment,
            ]);
            $this->doctor->getWallet()->add($amount);
        }elseif( !$this->lastTransaction ){
            $amount = ($this->appointment_fee - $this->discount);
            $this->doctor->getWallet()->add($amount);
        }
    }

    /**
     * Get the publicly accessible data
     * @return array
     */
    public function getPublicData()
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'doctor_id' => $this->doctor_id,
            'created' => $this->created_at,
            'scheduled' => $this->scheduled_at,
            'problem' => $this->patient_problem,
            'status' => $this->status,
            'appointment_code' => $this->appointment_code,
        ];
    }
}
