<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description'
    ];

    public function getPublicData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'image' => $this->image->link ?? null
        ];
    }

    public function image()
    {
        return $this->hasOne(Asset::class, 'model_id')->where('model', Department::class);
    }

    public function doctors()
    {
        return $this->hasMany(User::class, 'department_id')->where('role', 'doctor');
    }

    public function new_doctors()
    {
        return $this->belongsToMany(User::class, 'doctor_departments')->where('role', 'doctor');
    }
}
