<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cotizacion extends  Model
{
    use HasFactory;

    protected $table = 'cotizacion';

    protected $fillable = ['id_cliente','id_conductor','fecha_servicio','direccion_recogida','hora_recogida','direccion_destino','hora_estimada_salida','valor','observaciones','comentarios'];


    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

}
