<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class VehiculoConductores extends  Model
{
    use HasFactory;

    protected $table = 'vehiculos_conductores';

    protected $fillable = ['vehiculo_id','conductor_id'];


}
