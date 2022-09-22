<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Vehiculo extends  Model
{
    use HasFactory;

    protected $table = 'vehiculos';

    protected $fillable = ['placa','codigo_interno','modelo','linea','id_vehiculo_clase','id_vehiculo_marca','id_vehiculo_tipo_combustible','id_vehiculo_uso','servicio','capacidad_pasajeros','color','numero_chasis','numero_motor','cilindraje','departamento_id','ciudad_id','vinculado','convenio_firmado','propietario_id','activo'];


    public function clase(){
    	return $this->hasOne('App\Models\VehiculoClase','id','id_vehiculo_clase');
    }
    public function marca(){
    	return $this->hasOne('App\Models\VehiculoMarca','id','id_vehiculo_marca');
    }
}
