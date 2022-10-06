<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'total_earning', 'total_withdraw', 'pending_withdraw', 'status', 
    ];

    public function getBalance()
    {
        return $this->amount;
    }

    public function add($addAmount)
    {
        $payment = (int) settings('payment_to_doctor', config('system.payment.to_doctor'));
        $amount = $addAmount * $payment / 100;
        return $this->fill([
            'amount' => $this->amount + $amount,
            'total_earning' => $this->total_earning + $amount
        ])->save();
    }

    public function withdraw($amount)
    {
        return $this->fill([
            'amount' => $this->amount - $amount,
            'pending_withdraw' => $this->pending_withdraw + $amount
        ])->save();
    }

    public function confirmWithdraw($amount)
    {
        return $this->fill([
            'total_withdraw' => $this->total_withdraw + $amount,
            'pending_withdraw' => $this->pending_withdraw - $amount
        ])->save();
    }

    public function cancelWithdraw($amount)
    {
        return $this->fill([
            'pending_withdraw' => $this->pending_withdraw - $amount,
            'amount' => $this->amount + $amount,
        ])->save();
    }

}
