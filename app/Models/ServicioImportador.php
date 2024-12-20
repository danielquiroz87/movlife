<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class ServicioImportador extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'ordenes_servicio_importador';

    protected $fillable = ['id_cliente','placa','id_conductor_pago','id_conductor_servicio','id_pasajero','fecha_solicitud','fecha_servicio','origen','hora_recogida','hora_regreso', 'destino','semana','valor_conductor','valor_cliente','tipo_viaje','observaciones','comentarios','uri_sede','tipo_servicio','barrio','estado','motivo_cancelacion','tipo_anticipo','educador_coordinador','kilometros','tiempo','orden_compra','total_anticipos','total_abonos','fecha_pago','banco','doc_contable','nro_anticipo','valor_banco','observaciones_contabilidad','nro_pago','nro_factura','saldo','descuento','user_id','hora_infusion_inicial','hora_infusion_final', 'terapia','programa','jornada','horas_tiempo_adicional','tiempo_adicional','turno','imagen_conductor','hora_final_conductor','fecha_final_conductor','cedula_final_conductor','fecha_final_coordinador','preservicio_id','auxiliar','valor_auxiliar','auxiliar2','valor_auxiliar2','auxiliar3','valor_auxiliar3','importadoraux'];

    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','id_conductor_pago');
    }

    public function conductorServicio(){
        return $this->hasOne('App\Models\Conductor','id','id_conductor_servicio');
    }

    public function pasajero(){
    	return $this->hasOne('App\Models\Pasajero','id','id_pasajero');
    }
    public function coordinador(){
        return $this->hasOne('App\Models\Empleado','id','educador_coordinador');
    }

    public function sede(){
        return $this->hasOne('App\Models\Sedes','id','uri_sede');
    }

    public function detalle(){
    	return $this->hasOne('App\Models\OrdenServicioDetalle','id','orden_servicio_id');
    }

    public function vehiculo(){
        return $this->hasOne('App\Models\Vehiculo','placa','placa');
    }
     public function tipoServicio(){
        return $this->hasOne('App\Models\TipoServicios','id','tipo_servicio');
    }


}
