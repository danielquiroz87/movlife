<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Municipios extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'municipios';

    protected $fillable = ['nombre','departamento_id'];

    public function departamento(){
    	return $this->hasOne('App\Models\Municipios','id','ciudad_id');
    }
}
