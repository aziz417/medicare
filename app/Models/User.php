<?php

namespace App\Models;

use App\Traits\AppMeta;
use App\Traits\UserRelations;
use App\Traits\VerifyMobile;
use App\Contracts\MustVerifyMobile;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyMobile, MustVerifyEmail, JWTSubject
{
    use Notifiable, AppMeta, VerifyMobile, UserRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'mobile', 'password', 'role', 'submember_of', 'picture', 'department_id', 'status', 'is_desk_doctor'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be autoload when a user called.
     *
     * @var array
     */
    protected $with = [
        'meta'
    ];

    public function departments(){
        return $this->belongsToMany(Department::class)->withTimestamps();
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Scope for user role
     * @return QueryBuilder
     */
    public function scopeWhereRole($query, $role)
    {
        return $query->whereIn('role', arr()->wrap($role));
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn()
    {
        return 'User.Notifications.'.$this->id;
    }

    public function updateApiToken()
    {
        $this->forceFill([
            'api_token' => str()->random(80),
        ])->save();
    }

    /**
     * Check the user role
     * @param  string|array  $role user roles/role
     * @return boolean
     */
    public function isRole($role)
    {
        if( is_array($role) ){
            return in_array($this->role, $role);
        }
        return $this->role == $role;
    }
    public function isSuperAdmin()
    {
        return $this->isRole('master');
    }
    public function isAdmin($doctor = true)
    {
        $roles = ['master', 'admin'];
        if($doctor){ array_push($roles, 'doctor'); }
        return $this->isRole($roles);
    }

    /**
     * Check the user is need to verify
     * @param  string $type
     * @return bool
     */
    public function requiredVerification($type = 'email')
    {
        switch ($type) {
            case 'email':
                return $this->isRole(['admin', 'doctor']);
                break;
            case 'mobile':
                return $this->isRole(['user', 'patient']);
                break;

            default:
                return false;
                break;
        }
        return false;
    }

    public function avatar()
    {
        $default = $this->isRole('doctor') ? 'assets/content/doctor.png' : 'assets/content/user.png';
        return $this->picture ?? $default;
    }

    /**
     * Return the array
     * @return array
     */
    public function getPublicData($with = [])
    {
        $optional = arr()->wrap($with);
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'role' => $this->role,
            'picture' => asset($this->avatar()),
        ];
        if( in_array('meta', $optional) ){ $data['meta'] = $this->getSerializedMeta(); }

        $filtered = array_filter($optional, function($item){
            return !in_array($item, ['meta']);
        });
        foreach ($filtered as $item) {
            if( $value = $this->{$item} ){
                $data[$item] = $value;
            }
        }

        return $data;
    }
}
