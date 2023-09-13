<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Vehiculo extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'vehiculos';

    protected $fillable = ['placa','codigo_interno','modelo','linea','id_vehiculo_clase','id_vehiculo_marca','id_vehiculo_tipo_combustible','id_vehiculo_uso','servicio','capacidad_pasajeros','color','numero_chasis','numero_motor','cilindraje','departamento_id','ciudad_id','vinculado','convenio_firmado','propietario_id','activo','empresa_afiliadora'];


    public function clase(){
    	return $this->hasOne('App\Models\VehiculoClase','id','id_vehiculo_clase');
    }
    public function marca(){
    	return $this->hasOne('App\Models\VehiculoMarca','id','id_vehiculo_marca');
    }
    public function propietario(){
        return $this->hasOne('App\Models\Propietario','id','propietario_id');
    }
}
