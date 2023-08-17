<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Propietario extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'propietarios';

    protected $fillable = ['user_id','documento','nombres','apellidos','razon_social','email_contacto','telefono','celular','direccion_id','whatsapp','activo'];
}
