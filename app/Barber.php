<?php

namespace App;

use App\Traits\Guid;
use Illuminate\Database\Eloquent\Model;

class Barber extends Model
{
    use Guid;

    protected $keyType      = 'string';
    public $incrementing    = false;

    protected $fillable = [
        'name', 'phone_number','score','path_avatar','barbershop_id'
    ];

    public function barbershop(){
        return $this->belongsTo('\App\User','barbershop_id');
    }

    public function schedules(){
        return $this->hasMany('\App\Schedule','barber_id');
    }
}
