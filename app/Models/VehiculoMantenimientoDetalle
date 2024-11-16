<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class VehiculoMantenimientoDetalle extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];


    protected $table = 'vehiculos_mantenimientos_detalle';

    protected $fillable = ['mantenimiento_id','item_id','intervalo_km','km_ultima_revision','km_restantes','intervalo_years','fecha_ultima_revision','dias_restantes'];


}
