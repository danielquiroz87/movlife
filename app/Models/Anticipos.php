<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Anticipos extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'anticipos';

    protected $fillable = ['conductor_id','valor','estado','tipo','servicio_id','cliente_id','coordinador_id','valor_cliente','preservicio_id','observaciones','conductor_servicio_id'];

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_id');
    }

    public function conductorServicio(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_servicio_id');
    }

    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','cliente_id');
    }

    public function coordinador(){
    	return $this->hasOne('App\Models\Empleado','id','coordinador_id');
    }

    public function servicio(){
        return $this->hasOne('App\Models\Servicio','id','servicio_id');
    }

}
