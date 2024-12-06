<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class SigCategorias extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];
    
    protected $table = 'sig_categorias';

    protected $fillable = ['mastercategoriasig_id','nombre'];
}