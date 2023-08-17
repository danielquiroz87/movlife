<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class FuecRutas extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'fuec_rutas';

    protected $fillable = ['departamento_origen','origen','departamento_destino','destino'];

    public function getDepartamentoOrigen(){
    	return $this->hasOne('App\Models\Departamentos','id','departamento_origen');
    }

    public function getDepartamentoDestino(){
    	return $this->hasOne('App\Models\Departamentos','id','departamento_destino');
    }


}
