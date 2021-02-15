<?php

namespace App;


use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Guid;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{

    use Notifiable, Guid;

    protected $keyType      = 'string';
    public $incrementing    = false;

    protected $fillable = [
        'name', 'email', 'password','score','path_avatar','type','city','neighborhood','street','addressNumber','latitude','longitude','State','Country'
    ];

    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function barbers(){
        return $this->hasMany('\App\Barber','barbershop_id');
    }

    public function schedules(){
        return $this->hasMany('\App\Schedule','client_id');
    }

    public function feedbacks(){
        return $this->hasMany('\App\Feedback','barbershop_id');
    }
}
