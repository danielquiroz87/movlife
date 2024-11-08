<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class PreServicio extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'pre_ordenes_servicio';

    protected $fillable = ['id_cliente','cliente_documento','cliente_nombres','cliente_apellidos','cliente_email','cliente_celular','fecha_solicitud','fecha_servicio','hora_recogida','hora_regreso','origen','destino','semana','valor_conductor','valor_cliente','tipo_viaje','observaciones','comentarios','uri_sede','tipo_servicio','barrio','estado','motivo_cancelacion','tipo_anticipo','educador_coordinador','kilometros','tiempo','orden_compra','total_anticipos','total_abonos','fecha_pago','banco','doc_contable','nro_anticipo','valor_banco','observaciones_contabilidad','nro_pago','nro_factura','saldo','descuento','user_id','hora_infusion_inicial','hora_infusion_final', 'terapia','programa','jornada','horas_tiempo_adicional','tiempo_adicional','turno','pasajero_documento','pasajero_nombres','pasajero_apellidos','pasajero_email','pasajero_celular','placa','id_conductor_pago','id_conductor_servicio'];

    public function cliente(){
    	return $this->hasOne('App\Models\Cliente','id','id_cliente');
    }

    public function pasajero(){
    	return $this->hasOne('App\Models\Pasajero','id','pasajero_id');
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
    
    public function tipoServicio(){
        return $this->hasOne('App\Models\TipoServicios','id','tipo_servicio');
    }

    public function tipoViaje(){
        $tipo="";
        switch ($this->tipo_viaje) {

            case '1':
                $tipo='Ida';
                break;
            case '2':
                $tipo='Ida y Regreso';
                break;
            case '3':
                $tipo='Regreso';
                break;
            case '4':
                $tipo='Multidestino';
                break;    
            default:
                # code...
                break;
        }

        return $tipo;
    }
    

}
