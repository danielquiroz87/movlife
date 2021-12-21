<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Conductor extends  Model
{
    use HasFactory;

    protected $table = 'conductores';

    protected $fillable = ['user_id','documento','nombres','apellidos','email_contacto','telefono','celular','direccion_id','whatsapp','activo'];
}
