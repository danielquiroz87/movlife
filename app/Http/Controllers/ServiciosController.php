<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Pasajero;
use App\Models\Municipios;


use App\Models\OrdenServicioDetalle;
use App\Models\Anticipos;
use App\Models\AnticiposAbonos;
use App\Models\Conductor;


use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\TipoServicios;
use App\Models\Sedes;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Direccion;
use Config;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Helpers\Helper\Helper;


class ServiciosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {   
        $servicios=Servicio::whereIn('estado',array(1,2,3));
        $filtros=$request->get('filtros');

        if(isset($filtros['estado'])){
            $estado=(int) $filtros['estado'];
            if($estado!="" || $estado>0){
                $servicios->where('estado','=',$estado);
            }
        }
        else{
            $filtros['estado']="";
        }
        if(isset($filtros['cliente'])){
            $cliente=(int) $filtros['cliente'];
            if($cliente!="" || $cliente>0){
                $servicios->where('id_cliente','=',$cliente);
            }
            
        }else{
             $filtros['cliente']="";
        }
        if(isset($filtros['conductor'])){
            $conductor=(int) $filtros['conductor'];
            if($conductor!="" || $conductor>0){
                $servicios->where('id_conductor_servicio','=',$conductor);
            }
            
        }else{
             $filtros['conductor']="";
        }

        if(isset($filtros['pasajero'])){
            
            $pasajero=$filtros['pasajero'];

            if($pasajero!="" ){

               $servicios ->leftJoin('pasajeros AS p', function($join){
                    $join->on('ordenes_servicio.id_pasajero', '=', 'p.id');
                   
            });
                 $servicios->where('p.nombres', 'LIKE', '%'.$pasajero.'%')
                            ->orwhere('p.apellidos', 'LIKE', '%'.$pasajero.'%'); 
            }
            
        }else{
             $filtros['pasajero']="";
        }

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $servicios->where('fecha_servicio','>=',$fecha_inicial); 
            }
            
        }else{
            $filtros['fecha_inicial']=date('Y-m-01');
        }
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $servicios->where('fecha_servicio','<=',$fecha_final); 
            }
            
        }else{
            $filtros['fecha_final']=date('Y-m-d');
        }

        //$servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        $servicios=$servicios->paginate(2);

        return view('servicios.index')->with(['servicios'=>$servicios,'filtros'=>$filtros]);
    }
    public function importar(){

        return view('servicios.importar');
 
    }

    public function importarsave(Request $request){

        $file = $request->file('file');

        $fopen=fopen($file->getRealPath(),'r');
        $importData_arr = array(); // Read through the file and store the contents as an array
        $i = 0;
        $error=false;
        //Read the contents of the uploaded file 
        while (($filedata = fgetcsv($fopen, 1000, ";")) !== FALSE) {
            $num = count($filedata);
            // Skip first row (Remove below comment if you want to skip the first row)
            if ($i == 0) {
                $i++;
                continue;
            }
            for ($c = 0; $c < $num; $c++) {
                $importData_arr[$i][] = $filedata[$c];
            }
            $i++;
        }

        fclose($fopen); //Close after reading

        $j = 0;
        $arr_servicios=array();
         foreach ($importData_arr as $importData) {
        $j++;
        $error=false;

        try {

            DB::beginTransaction();

            $fecha_solicitud=$importData[1];
            $fecha_solicitud=explode("/",$fecha_solicitud);
            $fecha_solicitud=$fecha_solicitud[2].'-'.$fecha_solicitud[1].'-'.$fecha_solicitud[0];
            $fecha_prestacion=$importData[2];
            $fecha_prestacion=explode("/",$fecha_prestacion);
            $fecha_prestacion=$fecha_prestacion[2].'-'.$fecha_prestacion[1].'-'.$fecha_prestacion[0];
            
            $str_tipo_servicio=$importData[3];
            $coordinador=$importData[4];
            $semana=$importData[5];
            $persona_transportar=$importData[6];
            $telefono_paciente=$importData[7];
            $nombre_cliente=$importData[8];
            $nombre_uri_sede=$importData[9];

            $ciudad=$importData[10];
            $depto=$importData[11];
            $cod_paciente=$importData[12];
            $direccion_recogida=$importData[13];
            $barrio=$importData[14];
            $origen=$importData[15];
            $destino=$importData[16];
            $kilometros=$importData[17];
            $tiempo=$importData[18];

            $hora_recogida=strtolower($importData[19]);
            
            if(strpos($hora_recogida, "a")){
                $hora_recogida=explode(" ", $hora_recogida);
                $hora_recogida=$hora_recogida[0];
                $hora_recogida=str_replace("am","", $hora_recogida);
            }
            if(strpos($hora_recogida, "p")){
                $hora_recogida=explode(" ", $hora_recogida);
                $hora_recogida=$hora_recogida[0];
                $hora_recogida=str_replace("pm","", $hora_recogida);
                $horas=explode(":",$hora_recogida);
                if($horas[0]>12){
                    $horas[0]=12+$horas[0];
                    $hora_recogida=$horas[0].':'.$horas[1].$horas[2];
                }
            }   

            $str_tipo_viaje=$importData[20];
            if($str_tipo_viaje!=""){
               if($str_tipo_viaje=="IDA"){
                    $tipo_viaje=1;
               }
               if($str_tipo_viaje=="IDA Y REGRESO"){
                    $tipo_viaje=2;
               }
               if($str_tipo_viaje=="REGRESO"){
                    $tipo_viaje=3;
               }
            }

            if($str_tipo_servicio!=""){
                $obj_tipo_servicio=TipoServicios::where('nombre', 'LIKE', '%'.$str_tipo_servicio.'%')->get()->first();
                if($obj_tipo_servicio){
                    $tipo_servicio=$obj_tipo_servicio->id;
                }else{
                    $tipo_servicio=1;
                }
            }



            $turno=$importData[21];
            if($turno=="N/A" || $turno==""){
                $turno=NULL;
            }
            $educadora_coordinadora=$importData[22];
            $hora_inf_inicial=$importData[23];
            $hora_inf_final=$importData[24];
            $observaciones=$importData[25];
            $terapia=$importData[26];
            $programa=$importData[27];
            $nombres_conductor_paga=$importData[28];
            $cedula_conductor_paga=$importData[29];
            $nombres_conductor_servicio=$importData[30];
            $telefono_conductor=$importData[31];
            $persona_pago=$importData[32];
            $cedula_persona_pago=$importData[33];
            $costo=$importData[34];
            $descuento=$importData[35]?$importData[35]:0;
            $precio_alimentacion=$importData[36];
            $tota_con_descuento=$importData[37];
            $tarifa_cliente=$importData[38];
            $placa=$importData[52];
            $cedula_placa=$importData[53];
            $exp=explode("-", $cedula_placa);
            $cedula_cond_servicio=$exp[0];

           
            $pasajero=false;
            if($cod_paciente!=""){
                $pasajero=Pasajero::where('codigo',$cod_paciente)->get()->first();
            }
            if(!$pasajero){
                $exp_telefono_paciente=explode("/", $telefono_paciente);
                $pasajero=Pasajero::where('celular',$exp_telefono_paciente[0])->get()->first();
            }
            if(!$pasajero){
               $pasajero=new \stdClass();
               $pasajero->id=null;
            }
           
            $id_cliente=null;
            if($nombre_cliente!="" && $nombre_cliente!="N/A"){

                $cliente=Cliente::where('nombres', 'LIKE', '%'.$nombre_cliente.'%')
                                ->orWhere('apellidos', 'LIKE', '%'.$nombre_cliente.'%')
                                ->orWhere('razon_social', 'LIKE', '%'.$nombre_cliente.'%')->get()->first();
                
                if($cliente){
                   $id_cliente=$cliente->id;  
                }
               
            }
            echo "hora_recogida=".$hora_recogida.'<br/>';

            $cond_pago=Conductor::where('documento',$cedula_persona_pago)->get()->first();
            $cond_serv=Conductor::where('documento',$cedula_cond_servicio)->get()->first();

            if(!$cond_pago){
                $cond_pago=new \stdClass();
                $cond_pago->id=null;
            }
            if(!$cond_serv){
                $cond_serv=new \stdClass();
                $cond_serv->id=null;
            }


            $servicio=new Servicio();
            $servicio->id_cliente=$id_cliente;
            $servicio->placa=$placa;
            $servicio->id_conductor_pago=$cond_pago->id;
            $servicio->id_conductor_servicio=$cond_serv->id;
            $servicio->id_pasajero=$pasajero->id;
            $servicio->fecha_solicitud=$fecha_solicitud;
            $servicio->fecha_servicio=$fecha_prestacion;
            $servicio->hora_recogida=$hora_recogida;    
            $servicio->semana=$semana;
            $servicio->barrio=$barrio;
            $servicio->origen=$origen;
            $servicio->destino=$destino;

            $servicio->tipo_viaje=$tipo_viaje;
            $servicio->tipo_servicio=$tipo_servicio;
            $servicio->valor_conductor=$costo;
            $servicio->valor_cliente=$tarifa_cliente;
            $servicio->descuento=$descuento;
            $servicio->turno=$turno;
            $servicio->observaciones=$observaciones;
            $servicio->hora_infusion_inicial=$hora_inf_inicial;
            $servicio->hora_infusion_final=$hora_inf_final;
            $servicio->educador_coordinador=$educadora_coordinadora;
            $servicio->terapia=$terapia;
            $servicio->programa=$programa;
            $servicio->estado=1;
            //$servicio->save();
            //DB::commit();
            
            if($j==1){
               // break;
            }


        } catch (\Exception $e) {
               $error=true;
               var_dump($e->getMessage());
                DB::rollBack();
                die();
        }

        }
        die();
        if(!$error){
            \Session::flash('flash_message','Servicios importados exitosamente!.');
        }else{
             \Session::flash('flash_bad_message','Error al tratar de impotar los servicios!.');
        }

        return redirect()->route('servicios');

    }

    public function new()
    { 
        $dt=new CotizacionDetalle();
        return view('servicios.new')->with(['detalle'=>$dt,'cotizacion'=>false]);
    }

    public function fromAddress($id,Request $request){
        $dt=CotizacionDetalle::find($id);
        $cotizacion=Cotizacion::find($dt->cotizacion_id);
        $data=[
            'detalle'=>$dt,
            'cotizacion'=>$cotizacion
        ];

        return view('servicios.new')->with($data);

    }

     public function edit($id)
    {   
        $servicio=Servicio::find($id);
        $cotizacion=Cotizacion::find($servicio->cotizacion_id);
        $detalle=OrdenServicioDetalle::where('orden_servicio_id',$servicio->id)->first();
        $tipo_servicios=TipoServicios::all();
        $sedes=Sedes::all();
        $empleados=Empleado::where('area_empresa','4')->get();


        return view('servicios.edit')->with(['servicio'=>$servicio,
                                             'cotizacion'=>$cotizacion,
                                             'detalle'=>$detalle,
                                             'tipo_servicios'=>$tipo_servicios,
                                             'sedes'=>$sedes,
                                             'empleados'=>$empleados
                                         ]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $servicio=new Servicio();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $vehiculo=Servicio::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'id_cliente' => 'required',
                'id_pasajero' => 'required',
                'id_conductor_pago' => 'required',
                'id_conductor_servicio' => 'required',
                'fecha_servicio' => 'required',
                'hora_recogida' => 'required',
                'origen' => 'required|max:600|min:3',
                'destino' => 'required|max:600|min:3',
                'tipo_viaje' => 'required',
                'valor_conductor' => 'required',
                'valor_cliente' => 'required',


            ]);   

          
        }else{

            $v = Validator::make($request->all(), [
                'id_cliente' => 'required',
                'id_pasajero' => 'required',
                'id_conductor_pago' => 'required',
                'id_conductor_servicio' => 'required',
                'fecha_servicio' => 'required',
                'hora_recogida' => 'required',
                'origen' => 'required|max:600|min:3',
                'destino' => 'required|max:600|min:3',
                'tipo_viaje' => 'required',
                'valor_conductor' => 'required',
                'valor_cliente' => 'required',
            ]);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

           
         if($is_new){

            $servicio->create($request->all());
            $servicio->user_id=Auth::user()->id;
            $servicio->save();
            \Session::flash('flash_message','Servicio agregado exitosamente!.');

             return redirect()->route('servicios');

         }else{
            $servicio=Servicio::find($request->get('id'));
            $servicio->update($request->all());
            $servicio->user_id=Auth::user()->id;
            
            //Tipo Anticipo
            if($request->get('tipo_anticipo')==1){
               $anticipo=Anticipos::where('conductor_id',$servicio->id_conductor_pago)->where('estado',0)->get()->first();

               if($anticipo){
                    $existe_abono=AnticiposAbonos::where('orden_servicio_id',$servicio->id)->get()->first();
                    if($existe_abono){
                        $abono=$existe_abono;
                    }else{
                        $abono=new AnticiposAbonos();
                        $abono->anticipo_id=$anticipo->id;
                        $abono->orden_servicio_id=$servicio->id;  
                    }
                    $abono->valor=$servicio->valor_conductor;
                    $abono->save();
               }
            }

            //Actualizamos valores de anticipos en la orden de servicio
            $total_anticipos=false;
            $total_abonos=false;
            $results_anticipos = DB::select( DB::raw("SELECT sum(valor) as 'total_anticipos' FROM anticipos WHERE estado=0 and conductor_id = :conductor_id"), array(
               'conductor_id' => $servicio->id_conductor_pago,
             ));

            $results_abonos = DB::select( DB::raw("SELECT sum(b.valor) as 'total_abonos' FROM anticipos_abonos b inner join anticipos a on b.anticipo_id=a.id WHERE a.estado=0 and a.conductor_id = :conductor_id"), array(
               'conductor_id' => $servicio->id_conductor_pago,
             ));
            if($results_anticipos){
                $total_anticipos=$results_anticipos[0]->total_anticipos;
            }
            if($results_abonos){
                $total_abonos=$results_abonos[0]->total_abonos;
            }
            if($total_anticipos && $total_abonos){

                $servicio->total_anticipos=$total_anticipos;
                $servicio->total_abonos=$total_abonos;
                
                if($total_anticipos>$total_abonos){
                    $saldo=$total_anticipos-$total_abonos;
                }else{
                    $saldo=$total_abonos-$total_anticipos;
                }
                
                if($saldo<0){
                    $saldo=0;
                }
                $descuento=0;
                $resta_descuento=$total_anticipos-$total_abonos;

                if($resta_descuento<0){
                    $descuento=$servicio->valor_conductor+$resta_descuento;
                }else{
                     $descuento=$servicio->valor_conductor;
                }
                if($descuento>=0){
                    $saldo=0;
                }
                if($resta_descuento<0){
                    $saldo=$total_abonos-$total_anticipos;
                }
                $servicio->descuento=$descuento;
                $servicio->saldo=$saldo;
                $servicio->save();


            }else{
                $servicio->saldo=$servicio->valor_conductor;
                $servicio->save();
            }
           
            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

             return redirect()->route('servicios');

         }


    }
   
    public function update()
    { 
       
    }

 
    public function delete($id){
        
        $servicio=Servicio::find($id);

        $servicio->delete();

        \Session::flash('flash_message','Servicio eliminado exitosamente!.');

        return redirect()->back();


    }


    public function descargar(){
        $servicios=Servicio::whereIn('estado',array(1,2,3))->orderBy('fecha_servicio','Asc')->get();
        $tipo_servicios=TipoServicios::all();
        $fecha=date('Y-m-d');
        $filename = 'consolidado-semanal-'.$fecha.'.xls';
        //header('Content-type: application/excel');
        //header('Content-Disposition: attachment; filename='.$filename);
        $tabla=view('servicios.descargar')->with(['servicios'=>$servicios,'tipo_servicios'=>$tipo_servicios])->render();
        echo $tabla;
        exit();
    }

    private function getRepository(){
        return Servicio::paginate(Config::get('global_settings.paginate'));
    }
}
