<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Empleado extends  Model
{
    use HasFactory;

    protected $table = 'empleados';

    protected $fillable = ['user_id','documento','nombres','apellidos','email_contacto','telefono','celular','direccion_id','whatsapp','cargo','activo','area_empresa'];
}
