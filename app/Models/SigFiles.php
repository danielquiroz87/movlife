<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class SigFiles extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];
    
    protected $table = 'sig_files';

    protected $fillable = ['subcategoriasig_id','nombre','file','extension'];
}
