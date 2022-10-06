<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_id', 'model', 'name', 'content', 'link', 'type'
    ];

    protected static function boot() {
        parent::boot();
        static::deleting(function($item) {
            delete_file(public_path($item->content));
        });
    }

    public function scopeWhereModel($query, $model)
    {
        return $query->where('model', $model);
    }
}
