<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class ConveniosEmpresariales extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'convenios_empresariales';

    protected $fillable = ['fecha_inicial','fecha_final','numero_resolucion','id_empresa','id_conductor','placa','user_id','convenio_firmado'];


     public function empresa(){
    	return $this->hasOne('App\Models\EmpresasConvenios','id','id_empresa');
    }

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','id_conductor');
    }


    public function vehiculo(){
        return $this->hasOne('App\Models\Vehiculo','placa','placa');
    }

  

  
}
