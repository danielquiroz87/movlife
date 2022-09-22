<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AnticiposAbonos extends  Model
{
    use HasFactory;

    protected $table = 'anticipos_abonos';

    protected $fillable = ['anticipo_id','valor','orden_servicio_id'];
}
