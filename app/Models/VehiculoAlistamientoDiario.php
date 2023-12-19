<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class VehiculoAlistamientoDiario extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'vehiculo_alistamiento_diario';

    protected $fillable = ['vehiculo_id','conductor_id','aprobado','observaciones_conductor','observaciones_movlife','revisado_por','kilometros'];

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_id');
    }
     public function vehiculo(){
    	return $this->hasOne('App\Models\Vehiculo','id','vehiculo_id');
    }

}
