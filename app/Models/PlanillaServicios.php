<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class PlanillaServicios extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'planilla_servicios';

    protected $fillable = ['fecha','cliente_id','conductor_id','file','aprobada','observaciones','user_id','uri_sede'];

    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','cliente_id');
    }

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_id');
    }
    public function sede(){
    	return $this->hasOne('App\Models\Sedes','id','uri_sede');
    }

}
