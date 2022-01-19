<?php

namespace App\Http\Helpers\Helper;

use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class Helper{

public static function getClientes(){
 	return Cliente::orderBy('nombres', 'Asc')->get();
}

public static function selectClientes(){
	$clientes=self::getClientes();
	$option_clientes="";
	foreach ($clientes as $cliente) { 
		$nombres=$cliente->documento.','.$cliente->nombres.' '.$cliente->apellidos;
		$option_clientes.='<option value="'.$cliente->id.'">'.$nombres.'</option>';
	}
	return $option_clientes;
}

public static function getDepartamentos(){
	return DB::table('departamentos')
         -> orderBy('nombre', 'asc')
         -> get();
}

public static function selectDepartamentos(){
	$departamentos=self::getDepartamentos();
	$option_departamentos="";
	foreach ($departamentos as $departamento) { 
		$nombres=$departamento->nombre;
		$option_clientes.='<option value="'.$departamento->id.'">'.$nombres.'</option>';
	}
	return $option_departamentos;
}

public static function getMunicipios($departamentoId){
	return DB::table('municipios')
		 -> where('id_departamento','=',$departamentoId)
         -> orderBy('nombre', 'asc')
         -> get();
}


public static function selectMunicipios(){
	$municipios=self::getMunicipios();
	$option_municipios="";
	foreach ($municipios as $municipio) { 
		$nombres=$municipio->nombre;
		$option_clientes.='<option value="'.$municipios->id.'">'.$nombres.'</option>';
	}
	return $option_municipios;
}


}