<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class ConductorHojaDeVida extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'conductor_hojadevida';

    protected $fillable = ['id','conductor_id','estrato','numero_hijos','eps','pensiones','arl','nivel_riesgo_arl'];


   
   
}
