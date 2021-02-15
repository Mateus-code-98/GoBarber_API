<?php

namespace App;

use App\Traits\Guid;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use Guid;

    protected $keyType      = 'string';
    public $incrementing    = false;

    protected $fillable = [
        'barber_id','client_id','date','barbershop_id','status'
    ];

    public function client(){
        return $this->belongsTo('\App\User','client_id');
    }

    public function barber(){
        return $this->belongsTo('\App\Barber','barber_id');
    }

    public function barbershop(){
        return $this->belongsTo('\App\User','barberShop_id');
    }
}
