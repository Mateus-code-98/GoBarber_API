<?php

namespace App;

use App\Traits\Guid;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use Guid;

    protected $keyType      = 'string';
    public $incrementing    = false;

    protected $fillable = [
        'client_id','score','content','barbershop_id'
    ];

    public function client(){
        return $this->belongsTo('\App\User','client_id');
    }

    public function barbershop(){
        return $this->belongsTo('\App\User','barberShop_id');
    }

}
