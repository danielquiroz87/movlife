<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Tarifario extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'tarifario';

    protected $fillable = ['origen',' destino',' tipo_vehiculo','tiempo','kilometros','valor_conductor','valor_adicional','valor_cliente','proveedor',' jornada','trayecto','observaciones','id_cliente'];


}
