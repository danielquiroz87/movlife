<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Auditoria extends  Model 
{
    use HasFactory;

   

    protected $table = 'audits';

    protected $fillable = [];


    public function usuario(){
    	return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getModulo(){
    	return str_replace('App\Models\\','' ,$this->auditable_type);
    }


}
