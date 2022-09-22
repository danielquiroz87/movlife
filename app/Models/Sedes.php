<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Sedes extends  Model
{
    use HasFactory;

    protected $table = 'sedes';

    protected $fillable = ['nombre','departamento_id','ciudad_id'];

    public function ciudad(){
    	return $this->hasOne('App\Models\Municipios','id','ciudad_id');
    }
}
