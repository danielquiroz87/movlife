<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Anticipos extends  Model
{
    use HasFactory;

    protected $table = 'anticipos';

    protected $fillable = ['conductor_id','valor','estado'];

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_id');
    }

}
