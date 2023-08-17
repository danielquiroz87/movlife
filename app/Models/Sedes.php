<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Sedes extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'sedes';

    protected $fillable = ['nombre','departamento_id','ciudad_id'];

    public function ciudad(){
    	return $this->hasOne('App\Models\Municipios','id','ciudad_id');
    }
}
