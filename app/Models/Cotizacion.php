<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Cotizacion extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'cotizacion';

    protected $fillable = ['id_cliente','forma_pago','fecha_cotizacion','fecha_vencimiento','fecha_servicio','hora_recogida','hora_salida','tiempo_adicional','horas_tiempo_adicional','direccion_recogida','direccion_destino','valor','cantidad','total','finalizada','observaciones','comentarios','foto_vehiculo','id_user','contacto_nombres','contacto_telefono','contacto_email','jornada','tipo_vehiculo','servicio_id','anticipo_id'];


    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function user(){
        return $this->hasOne('App\Models\User','id','id_user');
    }

    public function direcciones()
	{
	    return hasMany('App\Models\CotizacionDetalle','cotizacion_id');
	}

    public function detalle()
    {
        return $this->hasMany('App\Models\CotizacionDetalle','cotizacion_id');
    }

}
