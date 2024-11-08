<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Cliente extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'clientes';

    protected $fillable = ['user_id','documento','nombres','apellidos','razon_social','email_contacto','telefono','celular','direccion_id','whatsapp','activo','plazo_pagos'];



    public function direccion()
	{
	    return $this->hasOne('App\Models\Direccion','id','direccion_id');
	}

	public function usuario(){

		return $this->hasOne('App\Models\User','id','user_id');

	}


}



