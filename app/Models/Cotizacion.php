<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Cotizacion extends  Model
{
    use HasFactory;

    protected $table = 'cotizacion';

    protected $fillable = ['id_cliente','forma_pago','fecha_cotizacion','fecha_vencimiento','fecha_servicio','hora_recogida','hora_salida','tiempo_adicional','horas_tiempo_adicional','direccion_recogida','direccion_destino','valor','cantidad','total','finalizada','observaciones','comentarios','id_user'];


    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','id_user');
    }

    public function direcciones()
	{
	    return hasMany('App/Models/CotizacionDetalle','cotizacion_id');
	}

}
