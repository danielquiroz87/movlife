<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class OrdenServicioDetalle extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'orden_servicio_detalle';

    protected $fillable = ['orden_servicio_id','origen','destino','destino1','destino2','destino3','destino4','destino5'];


    public function servicio(){
    	return $this->hasOne('App\Models\Servicio','id','orden_servicio_id');
    }

}
