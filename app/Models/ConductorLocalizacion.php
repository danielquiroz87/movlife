<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class ConductorLocalizacion extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];
    
    protected $table = 'conductor_localizacion';

    protected $fillable = ['user_id','lat','long','placa','conductor_id'];
   
}
