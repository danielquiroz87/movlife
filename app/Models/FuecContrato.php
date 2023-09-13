<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class FuecContrato extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'fuec_contratos';

    protected $fillable = ['id_cliente','contrato','responsable_documento','responsable_nombres',
                        'responsable_telefono','responsable_direccion'];


    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }


  
}
