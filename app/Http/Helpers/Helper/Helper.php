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
public static function selectClientes($id=0){
	$clientes=self::getClientes();
	$option_clientes="<option value=''>Sin Cliente</option>";
	foreach ($clientes as $cliente) { 
		$nombres=$cliente->documento.','.$cliente->razon_social;
		if($id>0){
			$option_clientes.='<option value="'.$cliente->id.'" selected="selected">'.$nombres.'</option>';
		}
		else{
			$option_clientes.='<option value="'.$cliente->id.'">'.$nombres.'</option>';
		}
	}
	return $option_clientes;
}

public static function selectConductores($id=0){
	$conductores=self::getConductores();
	$option_conductores="";
	foreach ($conductores as $conductor) { 
		$nombres=$conductor->documento.','.$conductor->nombres.' '.$conductor->apellidos;
		if($id>0){
			$option_conductores.='<option value="'.$conductor->id.'" selected="selected">'.$nombres.'</option>';
		}else{
			$option_conductores.='<option value="'.$conductor->id.'">'.$nombres.'</option>';
		}
	}
	return $option_conductores;
}


public static function selectPasajeros($id=0){
	$pasajeros=self::getPasajeros();
	$option_pasajeros="";
	foreach ($pasajeros as $pasajero) { 
		$nombres=$pasajero->documento.','.$pasajero->nombres.' '.$pasajero->apellidos;
		if($id>0){
			$option_pasajeros.='<option value="'.$pasajero->id.'" selected="selected" >'.$nombres.'</option>';
		}else{
			$option_pasajeros.='<option value="'.$pasajero->id.'">'.$nombres.'</option>';
		}
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


public static function selectMunicipios($id_departamento=1){
	$municipios=self::getMunicipios($id_departamento);
	$option_municipios="<option value=''>Seleccione un municipio</option>";
	foreach ($municipios as $municipio) { 
		$nombres=$municipio->nombre;
		$option_municipios.='<option value="'.$municipio->id.'">'.$nombres.'</option>';
	}
	return $option_municipios;
}

public static function getClaseVehiculos(){
	return DB::table('vehiculos_clase')
         -> orderBy('nombre', 'asc')
         -> get();
}
public static function selectClaseVehiculos($id=0){
	$clase=self::getClaseVehiculos();
	$option_clase="<option value=''>Sin Cliente</option>";
	foreach ($clase as $clase) { 
		if($id>0){
			$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$clase->nombre.'</option>';
		}
		else{
			$option_clase.='<option value="'.$clase->id.'">'.$clase->nombre.'</option>';
		}
	}
	return $option_clase;
}


public static function getPropietarios(){
	return DB::table('propietarios')
         -> orderBy('nombres', 'asc')
         -> get();
}

public static function selectPropietarios($id=0){
	$clase=self::getPropietarios();
	$option_clase="<option value=''>Seleccione un Propietario</option>";
	foreach ($clase as $clase) { 
		$name=$clase->documento.','.$clase->nombres.' '.$clase->apellidos;
		if($id>0){
			$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$name. '</option>';
		}
		else{
			$option_clase.='<option value="'.$clase->id.'">'.$name.'</option>';
		}
	}
	return $option_clase;
}


}