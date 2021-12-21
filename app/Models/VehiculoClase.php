<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class VehiculoClase extends  Model
{
    use HasFactory;

    protected $table = 'vehiculos_clase';

    protected $fillable = ['nombre'];


}
