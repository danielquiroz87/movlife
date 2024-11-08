<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class EmpresasConvenios extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'empresas_convenios';

    protected $fillable = ['nit','razon_social','representante_legal_documento','representante_legal_nombres','email_contacto','telefono','celular','direccion_id','whatsapp','activo'];
}
