<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class TarifasTipoServicio extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'tarifas_tipo_servicio';

    protected $fillable = ['cliente_id','tipo_servicio','uri_sede','destino','valor_conductor','valor_cliente','observaciones'];

    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','cliente_id');
    }

    public function uri(){
    	return $this->hasOne('App\Models\Sedes','id','uri_sede');
    }

    public function tipoServicio(){
        return $this->hasOne('App\Models\TipoServicios','id','tipo_servicio');
    }

}
