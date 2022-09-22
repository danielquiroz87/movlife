<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Municipios extends  Model
{
    use HasFactory;

    protected $table = 'municipios';

    protected $fillable = ['nombre','departamento_id'];

    public function departamento(){
    	return $this->hasOne('App\Models\Municipios','id','ciudad_id');
    }
}
