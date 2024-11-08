<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\User;
use App\Models\Direccion;
use App\Models\CotizacionDetalle;
use App\Models\Tarifario;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;

use Config;

class CotizacionesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    public function index(Request $request)
    {   
        $cotizaciones=Cotizacion::where('estado','>=',1);
        $filtros=$request->get('filtros');


        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $cotizaciones->where('fecha_cotizacion','>=',$fecha_inicial); 
            }
        }else{
            $filtros['fecha_inicial']=date('01-m-Y');
        }
        
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $cotizaciones->where('fecha_cotizacion','<=',$fecha_final.' 23:59:59'); 
            }
        }
        else{
            $filtros['fecha_final']=date('d-m-Y'); 
        }

        if(isset($filtros['cliente'])){
            $cliente=$filtros['cliente'];
            if($cliente!=""){
               $cotizaciones->where('id_cliente',$cliente); 
            }
        }
        else{
            $filtros['cliente']="";
        }

        if(isset($filtros['valor'])){
            $valor=$filtros['valor'];
            if($valor!=""){
               $cotizaciones->where('valor','>=',$valor); 
            }
        }
        else{
            $filtros['valor']="";
        }

        if(isset($filtros['id'])){
            $id=$filtros['id'];
            if($id!=""){
               $cotizaciones=Cotizacion::where('id','=',$id); 
            }
        }
        else{
            $filtros['id']="";
        }


        $request->session()->put('filtros.cotizaciones', $filtros);
        $cotizaciones=$cotizaciones->paginate(Config::get('global_settings.paginate')); 

        return view('cotizaciones.index')->with(['cotizaciones'=>$cotizaciones,'filtros'=>$filtros]);
    }

    public function descargarExcel(Request $request)
    {   
        $cotizaciones=Cotizacion::where('estado','>=',1);

        $filtros=$request->session()->get('filtros.cotizaciones');
        $fecha_inicial=$filtros['fecha_inicial'];
        $fecha_final=$filtros['fecha_final'];


        $id="";
        if(isset($filtros['id'])){
            $id=$filtros['id'];
            if($id!=""){
               $cotizaciones->where('id','=',$id); 
            }
        }
        if($id==""){

            if(isset($filtros['fecha_inicial'])){
                $fecha_inicial=$filtros['fecha_inicial'];
                if($fecha_inicial!=""){
                   $cotizaciones->where('fecha_cotizacion','>=',$fecha_inicial); 
                }
            }
            
            if(isset($filtros['fecha_final'])){
                $fecha_final=$filtros['fecha_final'];
                if($fecha_final!=""){
                   $cotizaciones->where('fecha_cotizacion','<=',$fecha_final.' 23:59:59'); 
                }
                
            }
            if(isset($filtros['cliente'])){
                $cliente=$filtros['cliente'];
                if($cliente!=""){
                   $cotizaciones->where('id_cliente',$cliente); 
                }
            }
            if(isset($filtros['valor'])){
                $valor=$filtros['valor'];
                if($valor!=""){
                   $cotizaciones->where('valor','>=',$valor); 
                }
            }

        }
  
      
       

        $filename = 'cotizaciones-del-'.$fecha_inicial.'-al-'.$fecha_final.'.xls';
        $tabla= view('cotizaciones.excel')->with(['cotizaciones'=>$cotizaciones->get()]);
        
        if(isset($_GET['revisar'])){
            var_dump($cotizaciones);
        }else{
            header('Content-type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename='.$filename);
        }
        echo $tabla;
        exit();
        
    }
    

    public function new()
    { 
        return view('cotizaciones.new');
    }
    public function edit($id)
    {   
        $cotizacion=Cotizacion::find($id);
        $direcciones=CotizacionDetalle::where('cotizacion_id','=',$cotizacion->id)->get();    
        return view('cotizaciones.edit')->with(['cotizacion'=>$cotizacion,'direcciones'=>$direcciones]);

    }
    public function save(Request $request)
    { 

        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $cotizacion=new Cotizacion();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $cotizacion=Cotizacion::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), []);   


        }else{

            $v = Validator::make($request->all(), []);   


        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }


        if($is_new){

            $cotizacion->id_cliente=$request->id_cliente;
            $cotizacion->descripcion=$request->descripcion;
            $cotizacion->forma_pago=$request->forma_pago;

            $cotizacion->fecha_cotizacion=$request->fecha_cotizacion;
            $cotizacion->fecha_vencimiento=$request->fecha_vencimiento;
            $cotizacion->fecha_servicio=$request->fecha_servicio;
            $cotizacion->hora_recogida=$request->hora_recogida;
            $cotizacion->hora_salida=$request->hora_salida;
            $cotizacion->tipo_viaje=$request->tipo_viaje;
            $cotizacion->tiempo_adicional=$request->tiempo_adicional;
            $cotizacion->horas_tiempo_adicional=$request->horas_tiempo_adicional;
            $cotizacion->direccion_recogida=$request->origen;
            $cotizacion->direccion_destino=$request->destino;

            $cotizacion->valor=$request->valor_unitario;
            $cotizacion->cantidad=$request->cantidad;
            $cotizacion->total=$request->valor_unitario*$request->cantidad;
            $cotizacion->observaciones=$request->observaciones;
            $cotizacion->comentarios=$request->comentarios;
            $cotizacion->finalizada=0;
            $cotizacion->id_user=$request->id_user;
            $cotizacion->contacto_nombres=$request->contacto_nombres;
            $cotizacion->contacto_telefono=$request->contacto_telefono;
            $cotizacion->contacto_email=$request->contacto_email;
            $cotizacion->jornada=$request->jornada;
            $cotizacion->tipo_vehiculo=$request->tipo_vehiculo;
            $cotizacion->estado=$request->estado;
            $cotizacion->servicio_id=$request->servicio_id;
            $cotizacion->anticipo_id=$request->anticipo_id;
            $cotizacion->estado=1;


            $file = $request->file('foto');

            if(is_object($file)){
                $destinationPath = 'uploads';
                $file->move($destinationPath,$file->getClientOriginalName());
                $cotizacion->foto_vehiculo=$destinationPath.'/'.$file->getClientOriginalName();
            }

            $cotizacion->save();


            if($request->get('origen')!=""){

                $existe=CotizacionDetalle::where('origen',$request->origen)
                                            ->where('destino',$request->destino)
                                            ->where('cotizacion_id',$cotizacion->id)
                                            ->get()->first();
                if(!$existe){
                    $cd=new CotizacionDetalle();
                    $cd->cotizacion_id=$cotizacion->id;
                    $cd->origen=$request->get('origen');
                    $cd->destino=$request->get('destino');
                    $cd->destino2=$request->get('destino2');
                    $cd->destino3=$request->get('destino3');
                    $cd->destino4=$request->get('destino4');
                    $cd->destino5=$request->get('destino5');
                    $cd->valor=$request->get('valor_unitario',0);
                    $cd->cantidad=$request->get('cantidad',1);
                    $cd->hora_recogida=$request->get('hora_recogida');
                    $cd->hora_salida=$request->get('hora_salida');
                    $cd->tipo_viaje=$request->get('tipo_viaje');
            
                    $cd->total=($cd->cantidad*$cd->valor);
                    $cd->save();
                }


                //Guardar Tarifa
                $guardaT=$request->get('guardar_tarifa');
                
                if($guardaT && (int) $guardaT==1){

                    $tarifario=new Tarifario();
                    $tipo=$request->get('tipo_vehiculo');
                    $origen=$request->get('origen');
                    $destino=$request->get('destino');
                    $jornada=$request->get('jornada');
                   
                    $tarifario->tipo_vehiculo=$request->get('tipo_vehiculo');
                    $tarifario->origen=$request->get('origen');
                    $tarifario->destino=$request->get('destino');
                    $tarifario->jornada=$request->get('jornada');
                    $tarifario->kilometros=$request->get('kilometros');
                    $tarifario->tiempo=$request->get('tiempo');
                    $tarifario->valor_conductor=$request->get('valor_unitario');
                    $tarifario->valor_cliente=$request->get('valor_unitario');
                    $tarifario->jornada=$request->get('jornada');
                    $tarifario->trayecto=$request->get('tipo_viaje');
                    $tarifario->id_cliente=$request->get('id_cliente');
                    $tarifario->save();

                    \Session::flash('flash_message','Tarifa Guardada Exitosamente!.');

                }


          
            }
            
            //$user->create($request->all());
            \Session::flash('flash_message','Cotización agregada exitosamente!.');

            return redirect()->route('cotizaciones.edit',['id'=>$cotizacion->id]);

        }else{

            $cotizacion->id_cliente=$request->id_cliente;
            $cotizacion->descripcion=$request->descripcion;
            $cotizacion->forma_pago=$request->forma_pago;

            $cotizacion->fecha_cotizacion=$request->fecha_cotizacion;
            $cotizacion->fecha_vencimiento=$request->fecha_vencimiento;
            $cotizacion->fecha_servicio=$request->fecha_servicio;
            $cotizacion->hora_recogida=$request->hora_recogida;
            $cotizacion->hora_salida=$request->hora_salida;
            $cotizacion->tipo_viaje=$request->tipo_viaje;
            $cotizacion->tiempo_adicional=$request->tiempo_adicional;
            $cotizacion->horas_tiempo_adicional=$request->horas_tiempo_adicional;
            
            $cotizacion->valor=$request->valor_unitario;
            $cotizacion->cantidad=$request->cantidad;
            $cotizacion->total=$request->valor_unitario*$request->cantidad;
            $cotizacion->observaciones=$request->observaciones;
            $cotizacion->comentarios=$request->comentarios;
            $cotizacion->finalizada=$request->finalizada;
            $cotizacion->contacto_nombres=$request->contacto_nombres;
            $cotizacion->contacto_telefono=$request->contacto_telefono;
            $cotizacion->contacto_email=$request->contacto_email;
            $cotizacion->jornada=$request->jornada;
            $cotizacion->tipo_vehiculo=$request->tipo_vehiculo;
            $cotizacion->estado=$request->estado;
            $cotizacion->servicio_id=$request->servicio_id;
            $cotizacion->anticipo_id=$request->anticipo_id;


            $file = $request->file('foto');

            if(is_object($file)){
                $destinationPath = 'uploads';
                if($cotizacion->foto_vehiculo!=""){
                    unlink($cotizacion->foto_vehiculo);
                }
                $file->move($destinationPath,$file->getClientOriginalName());
                $cotizacion->foto_vehiculo=$destinationPath.'/'.$file->getClientOriginalName();
            }
            

            $cotizacion->save();

            if($request->get('origen')!=""){


                 $existe=CotizacionDetalle::where('origen',$request->origen)
                                            ->where('destino',$request->destino)
                                            ->get()->first();
                if(!$existe){

                    $cd=new CotizacionDetalle();
                    $cd->cotizacion_id=$cotizacion->id;
                    $cd->origen=$request->get('origen');
                    $cd->destino=$request->get('destino');
                    $cd->destino2=$request->get('destino2');
                    $cd->destino3=$request->get('destino3');
                    $cd->destino4=$request->get('destino4');
                    $cd->destino5=$request->get('destino5');
                    $cd->valor=$request->get('valor_unitario',0);
                    $cd->cantidad=$request->get('cantidad',1);
                    $cd->hora_recogida=$request->get('hora_recogida');
                    $cd->hora_salida=$request->get('hora_salida');
                    $cd->tipo_viaje=$request->get('tipo_viaje');
                    $cd->total=($cd->cantidad*$cd->valor);
                    $cd->save();
                }
            }

            \Session::flash('flash_message','Cotización actualizada exitosamente!.');

            return redirect()->back();

        }


    }
    public function saveItem(Cotizacion $cotizacion,Request $request){

        if($request->get('origen')!=""){


            //Guardar Tarifa
            $guardaT=$request->get('guardar_tarifa');
            if($guardaT && (int) $guardaT==1){

            $tarifario=new Tarifario();
            $tipo=$request->get('tipo_vehiculo');
            $origen=$request->get('origen');
            $destino=$request->get('destino');
            $jornada=$request->get('jornada');
           
            $tarifario->tipo_vehiculo=$request->get('tipo_vehiculo');
            $tarifario->origen=$request->get('origen');
            $tarifario->destino=$request->get('destino');
            $tarifario->jornada=$request->get('jornada');
            $tarifario->kilometros=$request->get('kilometros');
            $tarifario->tiempo=$request->get('tiempo');
            $tarifario->valor_conductor=$request->get('valor_unitario');
            $tarifario->valor_cliente=$request->get('valor_unitario');
            $tarifario->jornada=$request->get('jornada');
            $tarifario->trayecto=$request->get('tipo_viaje');
            $tarifario->id_cliente=$request->get('id_cliente');
            $tarifario->save();

            \Session::flash('flash_message','Tarifa Guardada Exitosamente!.');

            }

            $cd=new CotizacionDetalle();
            $cotizacion=Cotizacion::find($request->get('id'));
            $cd->cotizacion_id=$cotizacion->id;
            $cd->origen=$request->get('origen');
            $cd->destino=$request->get('destino');
            $cd->destino2=$request->get('destino2');
            $cd->destino3=$request->get('destino3');
            $cd->destino4=$request->get('destino4');
            $cd->destino5=$request->get('destino5');
            $cd->valor=$request->get('valor_unitario',0);
            $cd->cantidad=$request->get('cantidad',1);
            $cd->total=($cd->cantidad*$cd->valor);
            $cd->tarifario_id=$request->get('tarifario_id');
            $cd->fecha_servicio=$request->get('fecha_servicio');
            $cd->descripcion=$request->get('descripcion');
            $cd->hora_recogida=$request->get('hora_recogida');
            $cd->hora_salida=$request->get('hora_salida');
            $cd->tipo_viaje=$request->get('tipo_viaje');

            $cd->save();

            \Session::flash('flash_message','Cotización Actualizada Exitosamente!.');

        }

        return response()->json([
            'code' => '200',
            'message' => 'Cotización actualizada exitosamente!.',
        ]);
    }

    public function update()
    { 

    }
    public function delete($id){
        $cotizacion=Cotizacion::find($id);
        $cotizacion->delete();

        \Session::flash('flash_message','Cotización eliminada exitosamente!.');

        return redirect()->back();


    }
    public function deleteDetalle($id){

        $cotizacion=CotizacionDetalle::find($id);
        $cotizacion->delete();

        \Session::flash('flash_message','Item cotización eliminada exitosamente!.');

        return redirect()->back();


    }
    public function descargar($id){

        /*
        $cotizacion=Cotizacion::find($id);
        $detalle=$cotizacion->detalle();
        return view('cotizaciones.descargar')->with(['cotizacion'=>$cotizacion]);
        */
        
        $cotizacion=Cotizacion::find($id);
        $detalle=$cotizacion->detalle();

        //$filename = 'cotizacion-'.$id.'.xls';
        //header('Content-type: application/vnd.ms-excel; charset=UTF-8');
       // header('Content-Disposition: attachment; filename='.$filename);
        $tabla=view('cotizaciones.descargar')->with(['cotizacion'=>$cotizacion])->render();
        echo $tabla;
        exit();
        
    }

    public function matchTarifa(Request $request){
      
        $id_cliente=$request->get('id_cliente');
        $tipo_vehiculo=$request->get('tipo_vehiculo');
        $origen= $request->get('origen');
        $origen= $this->eliminar_tildes($origen);
        $destino=$request->get('destino');
        $destino=$this->eliminar_tildes($destino);
        $kilometros=$request->get('kilometros');
        $tiempo=$request->get('tiempo');
        $jornada=$request->get('jornada');
        $tipo_viaje=$request->get('tipo_viaje');

        $eTarifa=Tarifario::where('id_cliente',$id_cliente)
        ->where('tipo_vehiculo',$tipo_vehiculo)
        ->where('origen','LIKE', '%'.$origen.'%')
        ->where('destino','LIKE', '%'.$destino.'%')
        ->where('kilometros',$kilometros)
        //->where('tiempo',$tiempo)
        ->where('jornada',$jornada)
        ->where('trayecto',$tipo_viaje)->get()->first();


       
        if($eTarifa){
            return response()->json([
                'code' => '200',
                'data' => ['vconductor'=>$eTarifa->valor_conductor,
                'vcliente'=>$eTarifa->valor_cliente,
                'id'=>$eTarifa->id]
            ]);
        }else{
            return response()->json([
                'code' => '200',
                'data' => ['vconductor'=>0,
                'vcliente'=>0,
                'id'=>0]
            ]);
        }

    }

    function eliminar_tildes($cadena){
     //$cadena= preg_replace("/[^a-zA-Z0-9\_\-]+/", "", $cadena);
    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    $unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                                'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                                'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                                'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                                'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

    $cadena = strtr( $cadena, $unwanted_array );

    $cadena = utf8_encode($cadena);

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

    return ($cadena);
}

    private function getRepository(){
        return Cotizacion::paginate(25);
    }
}
