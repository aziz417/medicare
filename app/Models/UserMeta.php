<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $fillable = [ 'user_id', 'meta_key', 'meta_value' ];
    

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function getValue()
    {
    	$meta = $this->meta_value;
    	if( is_json($meta) ){
    		return json_decode($meta);
    	}
    	return $meta;
    }
}
