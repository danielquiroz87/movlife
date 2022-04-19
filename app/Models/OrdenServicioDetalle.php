<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class OrdenServicioDetalle extends  Model
{
    use HasFactory;

    protected $table = 'orden_servicio_detalle';

    protected $fillable = ['orden_servicio_id','origen','destino','destino1','destino2','destino3','destino4','destino5'];


    public function servicio(){
    	return $this->hasOne('App\Models\Servicio','id','orden_servicio_id');
    }

}
