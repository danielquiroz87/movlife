<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class VehiculoAlistamientoDiarioDetalle extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'vehiculo_alistamiento_diario_detalle';

    protected $fillable = ['alistamiento_id','item_id','item_on'];

    public function alistamiento(){
    	return $this->hasOne('App\Models\VehiculoAlistamientoDiario','id','alistamiento_id');
    }
     

}
