<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'gateway', 'method', 'amount', 'discount', 'discount_code', 'final_amount', 'received_amount', 'tax', 'response', 'payment_id', 'payment_url', 'description', 'approved_by', 'type', 'access_token', 'status'
    ];

    /**
     * The attributes that should be always append with the instance
     *
     * @var array
     */
    protected $append = [ 'tnx_id', 'appointment' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'approved_by' => 'array',
        'response' => 'array',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function($item) {
            $item->access_token = str()->random(30);
        });
    }

    /**
     * Check Status
     * @return boolean 
     */
    public function isPending()
    {
        return in_array($this->status, ['pending', 'waiting']);
    }
    public function isWaiting()
    {
        return in_array($this->status, ['submitted', 'waiting', 'due']);
    }
    public function isConfirmed()
    {
        return in_array($this->status, ['success', 'approved', 'confirmed', 'completed']);
    }
    public function isCanceled()
    {
        return in_array($this->status, ['missed', 'declined', 'canceled', 'rejected']);
    }

    public function getTnxIdAttribute()
    {
        return "#".sprintf('%03s', $this->id);
    }

    /**
     * Relation with all Transactions Model
     * @return mixed
     */
    public function appointments()
    {
        return $this->belongsToMany(
                    Appointment::class, 
                    'appointments_transactions', 
                    'appointment_id',
                    'transaction_id' 
                )->latest();
    }
    public function getAppointmentAttribute(){
        return $this->appointments()->latest()->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function action($name = 'approved', $data = [], $fill = [])
    {
        $data['user_id'] = auth()->id();
        $data['user_name'] = auth()->user()->name ?? 'System';
        $this->fill([
            'status' => $name,
            'approved_by' => $data
        ] + arr()->wrap($fill) )->save();
    }
}
