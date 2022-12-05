<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Conductor extends  Model
{
    use HasFactory;

    protected $table = 'conductores';

    protected $fillable = ['user_id','documento','nombres','apellidos','lugar_expedicion_documento','lugar_de_nacimiento','nombre_contacto','telefono_contacto','email_contacto','telefono','celular','direccion_id','whatsapp','activo','estado_civil'];


    public function hojavida(){
    	return $this->hasOne('App\Models\ConductorHojaDeVida');
    }
   
}
