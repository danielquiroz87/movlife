<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ConductorHojaDeVida extends  Model
{
    use HasFactory;

    protected $table = 'conductor_hojadevida';

    protected $fillable = ['id','conductor_id','estrato','numero_hijos','eps','pensiones','arl','nivel_riesgo_arl'];


   
   
}
