<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Propietario extends  Model
{
    use HasFactory;

    protected $table = 'propietarios';

    protected $fillable = ['user_id','documento','nombres','apellidos','razon_social','email_contacto','telefono','celular','direccion_id','whatsapp','activo'];
}
