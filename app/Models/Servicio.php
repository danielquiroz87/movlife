<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Servicio extends  Model
{
    use HasFactory;

    protected $table = 'ordenes_servicio';

    protected $fillable = ['id_cliente','id_conductor','id_pasajero','fecha_servicio','origen','hora_recogida','destino','hora_estimada_salida','semana','valor_conductor','valor_cliente','tipo_viaje','observaciones','comentarios','tipo_servicio','barrio'];

    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','id_conductor');
    }

    public function pasajero(){
    	return $this->hasOne('App\Models\Pasajero','id','id_pasajero');
    }

    public function detalle(){
    	return $this->hasOne('App\Models\OrdenServicioDetalle','id','orden_servicio_id');
    }


}
