<?php

namespace App\Http\Helpers\Helper;

use App\Models\Cliente;
use App\Models\User;

use App\Models\Conductor;
use App\Models\TipoDocumentos;
use App\Models\Documentos;
use App\Models\Vehiculo;
use App\Models\Servicio;
use App\Models\VehiculoUsos;
use App\Models\AnticiposAbonos;

use App\Models\Pasajero;
use App\Models\Departamentos;

use App\Models\Municipios;

use App\Http\Helpers\Helper\NumeroALetras;
use Illuminate\Support\Facades\DB;


class Helper{

public static function getUserName($id){
 	$user=User::find($id);
 	return $user->name;

}	

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
			if($id==$cliente->id){
				$option_clientes.='<option value="'.$cliente->id.'" selected="selected">'.$nombres.'</option>';
			}else{
				$option_clientes.='<option value="'.$cliente->id.'">'.$nombres.'</option>';

			}
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
				if($conductor->id==$id){
					$option_conductores.='<option value="'.$conductor->id.'" selected="selected">'.$nombres.'</option>';
				}else{
					$option_conductores.='<option value="'.$conductor->id.'">'.$nombres.'</option>';
				}
			
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
		$nombres=$pasajero->nombres.' '.$pasajero->apellidos;
		if($id>0 && $pasajero->id==$id){
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

public static function getNombreDepartamento($id){
	 $departamento= Departamentos::find($id);
	 if($departamento){
	 	 return $departamento->nombre;
	 	}else{
	 		return "N/A";
	 	}
	
}

public static function getNombreCiudad($id){
	 $municipio= Municipios::find($id);
	 if($municipio){
	 	 return $municipio->nombre;
	 	}else{
	 		return "N/A";
	 	}
	
}

public static function selectDepartamentos($id=0){
	$departamentos=self::getDepartamentos();
	$option_departamentos="";
	foreach ($departamentos as $departamento) { 
		$nombres=$departamento->nombre;
		if($id>0){
			if($departamento->id==$id){
				$option_departamentos.='<option value="'.$departamento->id.'" selected="selected">'.$nombres.'</option>';
			}else{
				$option_departamentos.='<option value="'.$departamento->id.'">'.$nombres.'</option>';
			}
		
		}else{
			$option_departamentos.='<option value="'.$departamento->id.'">'.$nombres.'</option>';
		}
		
	}
	return $option_departamentos;
}

public static function getMunicipios($departamentoId){
	return DB::table('municipios')
		 -> where('id_departamento','=',$departamentoId)
         -> orderBy('nombre', 'asc')
         -> get();
}


public static function selectMunicipios($id_departamento=1,$id=0){
	$municipios=self::getMunicipios($id_departamento);
	$option_municipios="<option value=''>Seleccione un municipio</option>";
	foreach ($municipios as $municipio) { 
		$nombres=$municipio->nombre;
		if($id>0){
			if($municipio->id==$id){
				$option_municipios.='<option value="'.$municipio->id.'" selected="selected">'.$nombres. '</option>';
			}else{
				$option_municipios.='<option value="'.$municipio->id.'">'.$nombres.'</option>';
			}
		}
		else{
			$option_municipios.='<option value="'.$municipio->id.'">'.$nombres.'</option>';
		}			

		
	}
	return $option_municipios;
}

public static function getClaseVehiculos(){
	return DB::table('vehiculos_clase')
         -> orderBy('nombre', 'asc')
         -> get();
}
public static function selectClaseVehiculos($id=0){
	$clases=self::getClaseVehiculos();
	$option_clase="<option value=''>Seleccione</option>";
	foreach ($clases as $clase) { 
		if($id>0){
			
			if($clase->id==$id){
				$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$clase->nombre. '</option>';
			}else{
				$option_clase.='<option value="'.$clase->id.'">'.$clase->nombre.'</option>';
			}
			//$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$clase->nombre.'</option>';
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
	$clases=self::getPropietarios();
	$option_clase="<option value=''>Seleccione un Propietario</option>";
	foreach ($clases as $clase) { 
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


public static function getVehiculosMarcas(){
	return DB::table('vehiculos_marcas')
         -> orderBy('nombre', 'asc')
         -> get();
}

public static function selectVehiculoMarca($id=0){
	$clases=self::getVehiculosMarcas();
	$option_clase="<option value=''>Seleccione</option>";
	foreach ($clases as $clase) { 
		if($id>0){
			if($clase->id==$id){
				$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$clase->nombre. '</option>';
			}else{
				$option_clase.='<option value="'.$clase->id.'">'.$clase->nombre.'</option>';
			}
			
		}
		else{
			$option_clase.='<option value="'.$clase->id.'">'.$clase->nombre.'</option>';
		}
	}
	return $option_clase;
}

public static function selectTipoServicios($id=0){
	$clases= DB::table('tipo_servicios')
         -> orderBy('id', 'asc')
         -> get();

	$option_clase="<option value=''>Seleccione</option>";
	
	foreach ($clases as $clase) { 
		if($id>0 && $clase->id==$id){
			$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$clase->nombre.'</option>';
		}
		else{
			$option_clase.='<option value="'.$clase->id.'">'.$clase->nombre.'</option>';
		}
	}
	return $option_clase;
}


public static function convertiraLetras($number, $decimals = 2){
       	$numero=new NumeroALetras();
        return $numero->toWords($number, $decimals);
}

public function totalClientes(){
	
	$clientes=Cliente::all()->count();
	return $clientes;
}

public function totalVentas(){
	$ventas=Servicio::select(DB::raw('sum(valor_cliente) as totalVentas'))->where('estado','=',3)->where('fecha_servicio','>=',date('Y-m-01'))->get()->first();
	

	return $ventas->totalVentas?number_format($ventas->totalVentas):0;
}

public function totalOrdenes(){
	$ordenes=Servicio::all()->count();
	return $ordenes;
}

public static function getDocumentosConductor($id_conductor){

	$tipo_documentos=TipoDocumentos::where('tipo_usuario',5)->get();
	$arr_documentos=array();
	foreach ($tipo_documentos as $tipo) {
		
		$existe=Documentos::where('id_registro',$id_conductor)
							->where('id_tipo_documento',$tipo->id)->get()->first();

		if($existe){
			if($existe->cara_frontal!="" || $existe->cara_trasera!="" ){
				$cargado='SI';
			}else{
				$cargado='NO';
			}
			$arr_documentos[$id_conductor][$tipo->id]=array('cargado'=>$cargado,
															'fecha_vencimiento'=>$existe->fecha_final);
		}else{
			$arr_documentos[$id_conductor][$tipo->id]=array('cargado'=>'NO',
															'fecha_vencimiento'=>'NA');
		}
	}

	return ($arr_documentos);

}

public static function getDocumentosVehiculo($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();


	$tipo_documentos=TipoDocumentos::where('tipo_usuario',6)->get();
	$arr_documentos=array();

	foreach ($tipo_documentos as $tipo) {
		
		$existe=Documentos::where('id_registro',$vehiculo->id)
							->where('id_tipo_documento',$tipo->id)->get()->first();

		if($existe){
			if($existe->cara_frontal!="" || $existe->cara_trasera!="" ){
				$cargado='SI';
			}else{
				$cargado='NO';
			}
			$arr_documentos[$vehiculo->placa][$tipo->id]=array('cargado'=>$cargado,
															'fecha_vencimiento'=>$existe->fecha_final);
		}else{
			$arr_documentos[$vehiculo->placa][$tipo->id]=array('cargado'=>'NO',
															'fecha_vencimiento'=>'NA');
		}
	}

	return ($arr_documentos);

}


public static function getFechasDias($fecha2){
	
	
	if($fecha2!="" && $fecha2!='NA'){
		$f1=new \DateTime(date('Y-m-d'));
		$f2=new \DateTime($fecha2);
		if($f2>=$f1){
			$diff=$f1->diff($f2);
			$days='+'.$diff->days.'/Vigente';
		}else{
			$diff=$f2->diff($f1);
			$days='-'.$diff->days.'/ Vencido';
		}
		return $days;
	}else{
		return 'NA';
	}
	

}

public static function getFechaBd($fecha){
	$exp_fecha=explode("/", $fecha);
	return $exp_fecha[2].'-'.$exp_fecha[1].'-'.$exp_fecha[0];
}

public function getUsoVehiculo($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();

	$nombre_uso="N/A";
	if($vehiculo->id_vehiculo_uso>0){
	 $uso=VehiculoUsos::find($vehiculo->id_vehiculo_uso);
	 $nombre_uso=$uso->nombre;
	}
	return $nombre_uso;
}

public function getMarcaVehiculo($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();
	return $vehiculo->marca->nombre;
	
}

public function getModeloVehiculo($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();
	return $vehiculo->modelo;
	
}

public function getEmpresaAfiliadora($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();
	$nombre="N/A";
	if($vehiculo->empresa_afiliadora!=""){
	 	$nombre=$vehiculo->empresa_afiliadora;
	}
	return $nombre;
}
public static function getEstadoServicio($id){
	$estados=[1=>'Iniciado',2=>'En Proceso',3=>'Completado',4=>'Cancelado'];
	return $estados[$id];
}

public static function getTipoAnticipo($id){
	if($id>=1 && $id<=4){
		$tipos=[1=>'Anticipo',2=>'Empleado',3=>'Factura',4=>'Nequi',5=>'Daviplata'];
		return $tipos[$id];
	}

	return "N/A";
	
}

public static function getIdAnticipo($id){
	
	$abono=AnticiposAbonos::where('orden_servicio_id',$id)->get()->first();
	if($abono){
		return $abono->anticipo_id;
	}
	return "";
	
}

public static function getPasajero($id){
	
	$pasajero=Pasajero::where('id',$id)->get()->first();
	if($pasajero){
		return $pasajero->nombres.' '.$pasajero->apellidos;
	}
	return "";
	
}

public function getCiudad($id){
	$m=Municipios::find($id);
	return $m->nombre;

}

}