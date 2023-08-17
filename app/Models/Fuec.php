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

    protected $fillable = ['placa','id_conductor','consecutivo','tipo','id_cliente','contrato','user_id','contrato','fecha_inicial','fecha_final','ruta_id','objeto_contrato_id','responsable_contrato','_token','is_new'];


     public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','id_conductor');
    }

    public function vehiculo(){
        return $this->hasOne('App\Models\Vehiculo','placa','placa');
    }

      public function ruta(){
        return $this->hasOne('App\Models\FuecRutas','id','ruta_id');
    }

  
}
