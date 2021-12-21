<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Direccion extends  Model
{
    use HasFactory;

    protected $table = 'direcciones';

    protected $fillable = ['departamento_id','ciudad_id','direccion1','direccion2','tipo_usuario','parent_id'];
}
