<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Pasajero extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


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
