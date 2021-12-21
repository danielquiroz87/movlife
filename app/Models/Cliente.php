<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cliente extends  Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = ['user_id','documento','nombres','apellidos','razon_social','email_contacto','telefono','celular','direccion_id','whatsapp','activo'];
}
