<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TipoDocumentos extends  Model
{
    use HasFactory;

    protected $table = 'tipo_documentos';

    protected $fillable = ['nombre','tipo_usuario'];
}
