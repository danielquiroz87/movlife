<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Documentos extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'documentos';

    protected $fillable = ['id_tipo_documento','fecha_inicial','fecha_final','numero_documento','nombre_entidad','extra1','id_registro','cara_frontal','cara_trasera'];
}
