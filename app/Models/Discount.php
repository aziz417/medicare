<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code', 'amount', 'is_percentage', 'expire_at', 'limit', 'available_for', 'used', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'expire_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'avaiable_for');
    }

    public function updateUses()
    {
        $this->fill([
            'used' => $this->used + 1
        ])->save();
    }

    public function hasLimit()
    {
        if( empty($this->limit) ){ return true; }
        return $this->limit > $this->used;
    }

    public function isExpired()
    {
        if( empty($this->expire_at) ){ return false; }
        return now()->greaterThanOrEqualTo($this->expire_at);
    }

    public function availableFor($user_id)
    {
        if( empty($this->avaiable_for) ){ return true; }
        return $this->avaiable_for == $user_id;
    }

    public function check($user = false)
    {
        return $this->hasLimit() && !$this->isExpired() && $this->availableFor($user);
    }

    public static function checkCode($code, $user = false)
    {
        $item = self::where('code', $code)->first();

        if( $item && $item->hasLimit() && !$item->isExpired() && $item->availableFor($user) ){
            return $item->amount;
        }
        return false;
    }
}
