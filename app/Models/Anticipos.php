<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;


class Anticipos extends  Model implements Auditable
{
    use HasFactory;

    use AuditableTrait;

    protected $guarded = [];

    protected $table = 'anticipos';

    protected $fillable = ['conductor_id','valor','estado'];

    public function conductor(){
    	return $this->hasOne('App\Models\Conductor','id','conductor_id');
    }

}
