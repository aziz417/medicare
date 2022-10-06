<?php

namespace App\Models;

use App\Casts\Json;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'room_id', 'message'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'message' => Json::class,
    ];

    /**
     * The attributes that should be autoload when a user called.
     *
     * @var array
     */
    protected $with = [ 'user' ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getPublicData()
    {
        return [
            'id' => $this->id,
            'room_id' => $this->room_id,
            'message' => $this->message,
            'created' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => $this->user->getPublicData()
        ];
    }
}
