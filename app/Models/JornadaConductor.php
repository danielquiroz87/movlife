<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class JornadaConductor extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'jornada_conductores';

    protected $fillable = ['conductor_id','inicio_jornada','inicio_servicios','fin_servicios','fin_jornada','estado','revisado_por'];


    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_id');
    }
  
}
