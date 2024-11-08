<?php
/*Clase de utilidades, llenado de selects y otros datos para dashboard y alertas en informes*/

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
use App\Models\Sedes;
use App\Models\FuecRutas;


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
 	return Cliente::orderBy('razon_social', 'Asc')
	->get();
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
		$nombres=$cliente->razon_social.','.$cliente->documento;
		
		if(is_array($id)){
			if(in_array($cliente->id,$id)){
				$option_clientes.='<option value="'.$cliente->id.'" selected="selected">'.$nombres.'</option>';
			}
		}
		
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
	$option_conductores="<option value=''>Seleccione Un Conductor</option>";
	foreach ($conductores as $conductor) { 
		$nombres=$conductor->nombres.' '.$conductor->apellidos.','.$conductor->documento;

			if(is_array($id)){
				if(in_array($conductor->id,$id)){
					$option_conductores.='<option value="'.$conductor->id.'" selected="selected">'.$nombres.'</option>';
				}
			}
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
	$option_pasajeros="<option value=''>Seleccione un Pasajero</option>";

	foreach ($pasajeros as $pasajero) { 
		$nombres=$pasajero->nombres.' '.$pasajero->apellidos.','.$pasajero->documento;

		if(is_array($id)){
			if(in_array($pasajero->id,$id)){
				$option_pasajeros.='<option value="'.$pasajero->id.'" selected="selected">'.$nombres.'</option>';
			}
		}
		
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
		$name=$clase->nombres.' '.$clase->apellidos.','.$clase->documento;
		if($id>0 && $clase->id==$id ){
			$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$name. '</option>';
		}
		else{
			$option_clase.='<option value="'.$clase->id.'">'.$name.'</option>';
		}
	}
	return $option_clase;
}


public static function getSedes(){

	return Sedes::orderBy('nombre', 'Asc')->get();
}

public static function selectSedes($id=0){
	$clases=self::getSedes();
	$option_clase="<option value=''>Seleccione una Sede</option>";
	foreach ($clases as $clase) { 
		$name=$clase->nombre;

		if(is_array($id)){
			if(in_array($clase->id,$id)){
				$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$name.'</option>';
			}
		}

		if($id>0 && $clase->id==$id){
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

public static function selectEmpresas($id=0){
	$clases= DB::table('empresas_convenios')
         -> orderBy('razon_social', 'asc')
         -> get();

	$option_clase="<option value=''>Seleccione</option>";
	
	foreach ($clases as $clase) { 
		if($id>0 && $clase->id==$id){
			$option_clase.='<option value="'.$clase->id.'" selected="selected">'.$clase->razon_social.'</option>';
		}
		else{
			$option_clase.='<option value="'.$clase->id.'">'.$clase->razon_social.'</option>';
		}
	}
	return $option_clase;
}


public static function convertiraLetras($number, $decimals = 2){
       	$numero=new NumeroALetras();
        return $numero->toWords($number, $decimals);
}

public static function totalClientes(){
	
	$clientes=Cliente::all()->count();
	return $clientes;
}

public static function totalVentas(){
	$ventas=Servicio::select(DB::raw('sum(valor_cliente) as totalVentas'))->where('estado','=',3)->where('fecha_servicio','>=',date('Y-m-01'))->get()->first();
	

	return $ventas->totalVentas?number_format($ventas->totalVentas):0;
}

public static function totalOrdenes(){
	$ordenes=Servicio::all()->count();
	return $ordenes;
}

public static function getDocumentosConductor($id_conductor,$format='y-m-d'){

	$tipo_documentos=TipoDocumentos::where('tipo_usuario',5)->get();
	$arr_documentos=array();
	foreach ($tipo_documentos as $tipo) {
		
		$existe=Documentos::where('id_registro',$id_conductor)
							->where('id_tipo_documento',$tipo->id)->get()->first();
      

		if($existe){
			$fecha_final='NA';
			$fecha_inicial='NA';

			if($existe->cara_frontal!="" || $existe->cara_trasera!="" ){
				$cargado='SI';
			}else{
				$cargado='NO';
			}
			if($existe->fecha_inicial!=""){
				$fecha_inicial=date($format,strtotime($existe->fecha_inicial));
			}
			if($existe->fecha_final!=""){
				$fecha_final=date($format,strtotime($existe->fecha_final));
			}

			$arr_documentos[$id_conductor][$tipo->id]=array('cargado'=>$cargado,
															'fecha_expedicion'=>date('d/m/Y',strtotime($fecha_inicial)),
															'fecha_vencimiento'=>date('d/m/Y',strtotime($fecha_final)),
															'numero'=>$existe->numero_documento,
															'nombre_entidad'=>$existe->nombre_entidad,
															'extra1'=>$existe->extra1
														);
		}else{
			$arr_documentos[$id_conductor][$tipo->id]=array('cargado'=>'NO',
															'fecha_expedicion'=>'NA',
															'fecha_vencimiento'=>'NA',
															'fecha_vencimiento_bd'=>'NA',
															'numero'=>'',
															'nombre_entidad'=>'NA',
															'extra1'=>'NA'
														);
		}
	}

	return ($arr_documentos);

}

public static function getDocumentosVehiculo($placa,$format='Y-m-d'){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();


	$tipo_documentos=TipoDocumentos::where('tipo_usuario',6)->get();
	$arr_documentos=array();

	foreach ($tipo_documentos as $tipo) {
		
		$existe=Documentos::where('id_registro',$vehiculo->id)
							->where('id_tipo_documento',$tipo->id)->get()->first();

		if($existe){
			$fecha_final='NA';
			$fecha_inicial='NA';

			if($existe->cara_frontal!="" || $existe->cara_trasera!="" ){
				$cargado='SI';
			}else{
				$cargado='NO';
			}
			if($existe->fecha_inicial!=""){
				$fecha_inicial=date($format,strtotime($existe->fecha_inicial));
			}
			if($existe->fecha_final!=""){
				$fecha_final=date($format,strtotime($existe->fecha_final));
			}
			$arr_documentos[$vehiculo->placa][$tipo->id]=array('cargado'=>$cargado,
															'fecha_expedicion'=>$fecha_inicial,
															'fecha_vencimiento'=>$fecha_final,
															'numero'=>$existe->numero_documento);
		}else{
			$arr_documentos[$vehiculo->placa][$tipo->id]=array('cargado'=>'NO',
															'fecha_expedicion'=>'NA',
															'fecha_vencimiento'=>'NA',
															'numero'=>'');
		}
	}

	return ($arr_documentos);

}

public static function getDocumentosObligatorios($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();

	$tipo_documentos=TipoDocumentos::where('tipo_usuario',6)->whereIn('id',[9,10,13,14])->get();
	$arr_documentos=array();

	$faltantes=0;

	foreach ($tipo_documentos as $tipo) {
		
		$existe=Documentos::where('id_registro',$vehiculo->id)
							->where('id_tipo_documento',$tipo->id)->get()->first();

		if($existe){
			if($existe->cara_frontal!="" || $existe->cara_trasera!="" ){
				$cargado='SI';
			}else{
				$cargado='NO';
			}
			/*$arr_documentos[$vehiculo->placa][$tipo->id]=array('cargado'=>$cargado,
															'fecha_vencimiento'=>$existe->fecha_final,
															'numero'=>$existe->numero_documento);
															*/
		}else{
			$arr_documentos[]=array('nombre'=>$tipo->nombre);
		}
	}

	return ($arr_documentos);

}


public static function getFechasDias($fecha2){
	
	
	if($fecha2!="" && $fecha2!='NA'){
		$fecha = \DateTime::createFromFormat('d/m/Y', $fecha2);
      	if(!$fecha){
        	$fecha = \DateTime::createFromFormat('Y-m-d', $fecha2);
        }
		
		$f1=new \DateTime(date('Y-m-d'));
		$f2=$fecha;
      	
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

public static function getUsoVehiculo($placa){

	$vehiculo=Vehiculo::where('placa',$placa)->get()->first();

	$nombre_uso="N/A";
	if($vehiculo->id_vehiculo_uso>0){
	 $uso=VehiculoUsos::find($vehiculo->id_vehiculo_uso);
	 $nombre_uso=$uso->nombre;
	}
	return $nombre_uso;
}

public static function getMarcaVehiculo($placa){

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
	$estados=[0=>'Pendiente',1=>'Iniciado',2=>'En Proceso',3=>'Completado',4=>'Cancelado'];
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

public static function getCiudad($id){
	$m=Municipios::find($id);
	return $m->nombre;

}


public static function getRutas(){

	return DB::table('rutas')
         -> orderBy('codigo', 'asc')
         -> get();
}

public function selectRutas($id=0){

	$option="<option value=''>Seleccione</option>";
	$rutas=self::getRutas();

	foreach ($rutas as $ruta) { 

		
		$str_ruta=$ruta->codigo.'-'.$ruta->origen_destino;
			
		if($id>0 && $ruta->id==$id){
			$option.='<option value="'.$ruta->id.'" selected="selected">'.$str_ruta.'</option>';
		}
		else{
			$option.='<option value="'.$ruta->id.'">'.$str_ruta.'</option>';
		}
	}
	return $option;
}

public static function selectEmpleadosDirectores($id=""){

	$objetos_empleados= DB::table('empleados')
		->where('area_empresa','=',5)
        ->orderBy('id', 'asc')
        ->get();

		$option="<option value=''>Seleccione</option>";

		foreach ($objetos_empleados as $objeto) { 

			if(is_array($id)){
				if(in_array($objeto->id,$id)){
					$option.='<option value="'.$objeto->id.'" selected="selected">'.$objeto->nombres.' '.$objeto->apellidos.'</option>';
				}
			}

			if($id>0 && $objeto->id==$id){
				$option.='<option value="'.$objeto->id.'" selected=true>'.$objeto->nombres.' '.$objeto->apellidos.'</option>';
			}else{
				$option.='<option value="'.$objeto->id.'">'.$objeto->nombres.' '.$objeto->apellidos.'</option>';
			}
		}
		return $option;	
}

public static function selectBancos($id=""){

	$objetos_bancos= DB::table('bancos_ach')
		->orderBy('nombre', 'asc')
        ->get();

		$option="<option value=''>Seleccione</option>";

		foreach ($objetos_bancos as $objeto) { 
			if($id>0 && $objeto->id==$id){
				$option.='<option value="'.$objeto->nombre.'" selected=true>'.$objeto->nombre.'</option>';
			}else{
				$option.='<option value="'.$objeto->nombre.'">'.$objeto->nombre.'</option>';
			}
		}
		return $option;	
}



public function selectObjetosContrato($id=0){

	$objetos_contrato= DB::table('fuec_objetos_contrato')
         -> orderBy('id', 'asc')
         -> get();
	

	$option="<option value=''>Seleccione</option>";

	foreach ($objetos_contrato as $objeto) { 
		
			
		if($id>0 && $objeto->id==$id){
			$option.='<option value="'.$objeto->id.'" selected="selected">'.$objeto->nombre.'</option>';
		}
		else{
			$option.='<option value="'.$objeto->id.'">'.$objeto->nombre.'</option>';
		}
	}
	return $option;
}


public static function alertaDocumentos($conductores,$placa,$fecha_final){
	
	$list_ids=implode(',', $conductores);

	$sql="select `tipo_documento`,tipo_usuario,`fecha_final`,nombres,DATEDIFF(fecha_final,'".$fecha_final."') as  resta from reporte_all_documentos where tipo_usuario='Conductor' and id_registro in ($list_ids) and `fecha_final`<'".$fecha_final."' having resta<0";
	$sql.=" union select `tipo_documento`,tipo_usuario,`fecha_final`,nombres, DATEDIFF(fecha_final,'$fecha_final') as  resta from reporte_all_documentos where tipo_usuario='Vehiculo' and nombres = ('$placa') and `fecha_final`<'$fecha_final' having resta<0 ";

	return DB::select($sql);

}

public static function getTotalAbonosAnticipos($anticipo_id){

	$abonos=AnticiposAbonos::where('anticipo_id','=',$anticipo_id)->get();
    $total=0;
	foreach($abonos as $abono ){
		$total+=$abono->valor;
	}
	return $total;
}
public static function  limpiarCadena($cadena){

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    //$cadena = utf8_encode($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}



}