<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Pasajero extends  Model
{
    use HasFactory;

    protected $table = 'pasajeros';

    protected $fillable = ['user_id','documento','nombres','apellidos','email_contacto','telefono','celular','direccion_id','whatsapp','activo'];


     public function direccion()
	{
	    return $this->hasOne('App\Models\Direccion','id','direccion_id');
	}

    function scopeWithName($query, $fullname)
	{
	    
	    // Search each Name Field for any specified Name
	    return self::where(function($query) use ($fullname) {
	        $query->whereRaw('CONCAT(nombres," ",apellidos) LIKE "%'.$fullname.'%"');
	     
	    });
	}

	 function scopeWithNameAndCity($query, $fullname,$city)
	{
	    
	    // Search each Name Field for any specified Name
	    return self::where(function($query) use ($fullname,$city) {
	        $query->join('direcciones','direccion_id','=','id')
	        ->where('ciudad_id',$city);
	        $query->whereRaw('CONCAT(nombres," ",apellidos) LIKE "%'.$fullname.'%"');
	     
	    });
	}
}
