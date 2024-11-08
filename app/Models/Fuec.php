<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Fuec extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'fuec';

    protected $fillable = ['placa','id_conductor','consecutivo','tipo','id_cliente','contrato','user_id','contrato','fecha_inicial','fecha_final','ruta_id','objeto_contrato_id','creado_por','_token','is_new','id_conductor_2','id_conductor_3','id_conductor_4','id_contrato_cliente'];


     public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','id_conductor');
    }

    public function conductor2(){
        return $this->hasOne('App\Models\Conductor','id','id_conductor_2');
    }

    public function conductor3(){
        return $this->hasOne('App\Models\Conductor','id','id_conductor_3');
    }

    public function conductor4(){
        return $this->hasOne('App\Models\Conductor','id','id_conductor_4');
    }

    public function vehiculo(){
        return $this->hasOne('App\Models\Vehiculo','placa','placa');
    }

    public function ruta(){
        return $this->hasOne('App\Models\Rutas','id','ruta_id');
    }

    public function objeto_contrato(){
        return $this->hasOne('App\Models\FuecObjetosContrato','id','objeto_contrato_id');
    }

  
}
