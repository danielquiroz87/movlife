<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class CotizacionDetalle extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'cotizacion_detalle';

    protected $fillable = ['cotizacion_id','origen','destino','destino1','destino2','destino3','destino4','destino5','valor','cantidad','total'];


    public function cotizacion(){
    	return $this->hasOne('App\Models\Cotizacion','id','cotizacion_id');
    }

}
