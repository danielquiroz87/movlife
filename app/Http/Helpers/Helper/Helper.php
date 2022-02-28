<?php

namespace App\Http\Helpers\Helper;

use App\Models\Cliente;
use App\Models\Conductor;
use App\Models\Pasajero;


use Illuminate\Support\Facades\DB;

class Helper{

public static function getClientes(){
 	return Cliente::orderBy('nombres', 'Asc')->get();
}
public static function getConductores(){
 	return Conductor::orderBy('nombres', 'Asc')->get();
}
public static function getPasajeros(){
 	return Pasajero::orderBy('nombres', 'Asc')->get();
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

public static function selectConductores(){
	$conductores=self::getConductores();
	$option_conductores="";
	foreach ($conductores as $conductor) { 
		$nombres=$conductor->documento.','.$conductor->nombres.' '.$conductor->apellidos;
		$option_conductores.='<option value="'.$conductor->id.'">'.$nombres.'</option>';
	}
	return $option_conductores;
}


public static function selectPasajeros(){
	$pasajeros=self::getPasajeros();
	$option_pasajeros="";
	foreach ($pasajeros as $pasajero) { 
		$nombres=$pasajero->documento.','.$pasajero->nombres.' '.$pasajero->apellidos;
		$option_pasajeros.='<option value="'.$pasajero->id.'">'.$nombres.'</option>';
	}
	return $option_pasajeros;
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
		$option_departamentos.='<option value="'.$departamento->id.'">'.$nombres.'</option>';
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
	$municipios=self::getMunicipios(2);
	$option_municipios="";
	foreach ($municipios as $municipio) { 
		$nombres=$municipio->nombre;
		$option_municipios.='<option value="'.$municipio->id.'">'.$nombres.'</option>';
	}
	return $option_municipios;
}


}