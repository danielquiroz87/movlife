<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\Cliente;
use App\Models\Pasajero;
use App\Models\Municipios;
use App\Models\PreServicio;


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
use App\Models\Vehiculo;
use App\Models\Fuec;

use Config;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Helpers\Helper\Helper;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;



class ServiciosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
       $name=$request->route()->getName();
       if($name=='web.preservicio' || $name=='web.preservicios.save' ){

       }
       else{
            $this->middleware('auth');
       }
    }

    public function testEmail(Request $request,$id){

        $servicio=Servicio::find($id);

        $body = view('servicios.email',compact('servicio'))->render();

        $mail = new PHPMailer(true);

        try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'danykyroz@gmail.com';                     //SMTP username
        $mail->Password   = 'uevp blus zygo yols';                               //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('danykyroz@gmail.com', 'Movlife');
        if($servicio->pasajero->email_contacto!=""){
            $mail->addAddress($servicio->pasajero->email_contacto, $servicio->pasajero->nombres); 
        }
        if($servicio->cliente->email!=""){
            $mail->addCC($servicio->cliente->email);
        }
        $mail->addBCC('daniel.quiroz@epayco.com');
        $mail->isHTML(true);                                 
        $mail->Subject = 'Nuevo Servicio Movlife #'.$servicio->id;
        $mail->Body    = $body;
     
        $mail->send();
        echo 'Message has been sent';

        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        die();
    }


     public function sendEmail($id){

        $servicio=Servicio::find($id);

        $body = view('servicios.email',compact('servicio'))->render();
        
        $mail = new PHPMailer(true);

        try {
        //Server settings
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'danykyroz@gmail.com';                     //SMTP username
        $mail->Password   = 'uevp blus zygo yols';                               //SMTP password
        $mail->SMTPSecure = 'tls';            //Enable implicit TLS encryption
        $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('danykyroz@gmail.com', 'Movlife');
        if($servicio->pasajero->email_contacto!=""){
            $mail->addAddress($servicio->pasajero->email_contacto, $servicio->pasajero->nombres); 
        }
        if($servicio->cliente->email!=""){
            $mail->addCC($servicio->cliente->email);
        }
        $mail->addBCC('daniel.quiroz@epayco.com');
        $mail->isHTML(true);                                 
        $mail->Subject = 'Nuevo Servicio Movlife #'.$servicio->id;
        $mail->Body    = $body;
     
        $mail->send();
        $response='Message has been sent';

        } catch (Exception $e) {
            $response= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return $response;
    }


     public function listar_preservicios(Request $request){
        $servicios=PreServicio::whereIn('estado',array(1,2,3));
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
        
        if(isset($filtros['uri_sede'])){
            $uri_sede=(int) $filtros['uri_sede'];
            if($uri_sede!="" || $uri_sede>0){
                $servicios->where('uri_sede','=',$uri_sede);
            }
            
        }else{
             $filtros['uri_sede']="";
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
        $request->session()->put('filtros_servicios', $filtros);

        $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        //$servicios=$servicios->paginate(2);

        return view('servicios.preservicios_index')->with(['servicios'=>$servicios,'filtros'=>$filtros]);




        return view('servicios.listar_preservicios');
    }

    public function preservicio(Request $request){
        
        $sedes=Sedes::all();

        return view('servicios.preservicio')->with(['sedes'=>$sedes]);
    }

    public function fromPreservicio(Request $request,$id){
        
        $preservicio=PreServicio::find($id);
        
        $cotizacion=new Cotizacion();
        $cotizacion->id_cliente=$preservicio->id_cliente;
        $cotizacion->fecha_servicio=$preservicio->fecha_servicio;
        $cotizacion->hora_recogida=$preservicio->hora_recogida;
        $cotizacion->hora_salida=$preservicio->hora_regreso;
        $cotizacion->direccion_recogida=$preservicio->origen;
        $cotizacion->direccion_destino=$preservicio->destino;

        $dt=new CotizacionDetalle();
        $dt->origen=$preservicio->origen;
        $dt->destino=$preservicio->destino;

        $servicio=new Servicio();
        $servicio->id_cliente=$preservicio->id_cliente;
        $servicio->fecha_solicitud=$preservicio->fecha_solicitud;
        $servicio->fecha_servicio=$preservicio->fecha_servicio;
        $servicio->hora_recogida=$preservicio->hora_recogida;
        $servicio->hora_regreso=$preservicio->hora_regreso;
        $servicio->id_pasajero=$preservicio->pasajero_id;

        $servicio->barrio=$preservicio->barrio;
        $servicio->origen=$preservicio->origen;
        $servicio->destino=$preservicio->destino;
        $servicio->tipo_viaje=$preservicio->tipo_viaje;
        $servicio->tipo_servicio=$preservicio->tipo_servicio;
        $servicio->uri_sede=$preservicio->uri_sede;
        $servicio->observaciones=$preservicio->observaciones;
        $servicio->estado=1;

        $sedes=Sedes::all();


        return view('servicios.new')->with(['servicio'=>$servicio,'cotizacion'=>$cotizacion,'detalle'=>$dt,'sedes'=>$sedes]);
    }

    public function preserviciosave(Request $request){

        try{


       $preservicio=new PreServicio();
       $documento=$request->get('cliente_documento');
       $documento_pasajero= $request->get('pasajero_documento');

       $existe_cliente=Cliente::where('documento',$documento)->get()->first();
       if($existe_cliente){
            $preservicio->id_cliente=$existe_cliente->id;
            $preservicio->cliente_documento=$existe_cliente->documento;
            $preservicio->cliente_nombres=$existe_cliente->nombres;
            $preservicio->cliente_apellidos=$existe_cliente->apellidos;
            $preservicio->cliente_email=$existe_cliente->email_contacto;
            $preservicio->cliente_celular=$existe_cliente->celular;

       }else{
            $preservicio->cliente_documento=$request->get('cliente_documento');
            $preservicio->cliente_nombres=$request->get('cliente_nombres');
            $preservicio->cliente_apellidos=$request->get('cliente_apellidos');
            $preservicio->cliente_email=$request->get('cliente_email');
            $preservicio->cliente_celular=$request->get('cliente_celular');
       }
       $existe_pasajero=Pasajero::where('documento',$documento_pasajero)->get()->first();
       if($existe_pasajero){

            $preservicio->pasajero_id=$existe_pasajero->id;
            $preservicio->pasajero_documento=$existe_pasajero->documento;
            $preservicio->pasajero_nombres=$existe_pasajero->nombres;
            $preservicio->pasajero_apellidos=$existe_pasajero->apellidos;
            $preservicio->pasajero_email=$existe_pasajero->email_contacto;
            $preservicio->pasajero_celular=$existe_pasajero->celular;

       }else{

            $preservicio->pasajero_documento=$request->get('pasajero_documento');
            $preservicio->pasajero_nombres=$request->get('pasajero_nombres');
            $preservicio->pasajero_apellidos=$request->get('pasajero_apellidos');
            $preservicio->pasajero_email=$request->get('pasajero_email');
            $preservicio->pasajero_celular=$request->get('pasajero_celular');
       } 

       $preservicio->fecha_solicitud=$request->get('fecha_solicitud');
       $preservicio->fecha_servicio=$request->get('fecha_servicio');
       $preservicio->hora_recogida=$request->get('hora_recogida');
       $preservicio->origen=$request->get('origen');
       $preservicio->destino=$request->get('destino');
       $preservicio->tipo_viaje=$request->get('tipo_viaje');
       $preservicio->tipo_servicio=$request->get('tipo_servicio');
       $preservicio->uri_sede=$request->get('uri_sede');
       $preservicio->observaciones=$request->get('observaciones');
       $preservicio->estado=1;
       $preservicio->save();

        \Session::flash('flash_message','Servicio enviado exitosamente!.');

       }catch(Exception $ex){
            \Session::flash('bad_message','Error al tratar de guardar el servicio!.');
       }

        return redirect()->route('web.preservicio');

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

        if(isset($filtros['conductor_pago'])){
            $conductor_pago=(int) $filtros['conductor_pago'];
            if($conductor_pago!="" || $conductor_pago>0){
                $servicios->where('id_conductor_pago','=',$conductor_pago);
            }
            
        }else{
             $filtros['conductor_pago']="";
        }

        if(isset($filtros['uri_sede'])){
            $uri_sede=(int) $filtros['uri_sede'];
            if($uri_sede!="" || $uri_sede>0){
                $servicios->where('uri_sede','=',$uri_sede);
            }
            
        }else{
             $filtros['uri_sede']="";
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
        $request->session()->put('filtros_servicios', $filtros);

        $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        //$servicios=$servicios->paginate(2);

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
        while (($filedata = fgetcsv($fopen, 2000, ";")) !== FALSE) {
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

        DB::beginTransaction();

        foreach ($importData_arr as $importData) {
        $j++;
        $error=false;
        $message="";
        
        try {
        

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
            $persona_transportar=trim($persona_transportar);
            $telefono_paciente=$importData[7];
            $nombre_cliente=$importData[8];
            $nombre_uri_sede=$importData[9];

            $ciudad=$importData[10];
            $depto=$importData[11];
            $cod_paciente=trim($importData[12]);
            if($cod_paciente=="N/A"){
                $cod_paciente=0;
            }
            $direccion_recogida=$importData[13];
            $barrio=$importData[14];
            $origen=$importData[15];
            $destino=$importData[16];
            $kilometros=$importData[17];
            $kilometros=str_replace(",",".", $kilometros);
            if($kilometros=="N/A"){
                $kilometros=0;
            }

            $tiempo=$importData[18];



            $str_tipo_viaje=$importData[20];
            $hora_recogida=strtolower($importData[19]);
            $hora_recogida=trim($hora_recogida);
            $hora_regreso=NULL;

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


            if($hora_recogida=="n/a" || $hora_recogida=="no aplica"){
                $hora_recogida=NULL;
            }
            
            if(strpos($hora_recogida, "/")){
                $horas_explode=explode("/", $hora_recogida);
                if(count($horas_explode)>=2){
                    $hora_recogida=trim($horas_explode[0]);
                    if($tipo_viaje==2){
                        $hora_regreso=trim($horas_explode[1]);
                    }
                    
                }    
            }
            

            
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
                    $hora_recogida=$horas[0].':'.$horas[1].':00';
                }
            }   

            if(strpos($hora_regreso, "a")){
                $hora_regreso=explode(" ", $hora_regreso);
                $hora_regreso=$hora_regreso[0];
                $hora_regreso=str_replace("am","", $hora_regreso);
            }
            if(strpos($hora_regreso, "p")){
                $hora_regreso=explode(" ", $hora_regreso);
                $hora_regreso=$hora_regreso[0];
                $hora_regreso=str_replace("pm","", $hora_regreso);
                $horas=explode(":",$hora_regreso);
                if($horas[0]>12){
                    $horas[0]=12+$horas[0];
                    $hora_regreso=$horas[0].':'.$horas[1].':00';
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
            
            if($turno=="N/A" || $turno=="" || $turno=="NO APLICA" ){
                $turno=NULL;
            }

            $obj_uri=false;


            if($nombre_uri_sede!="" || $nombre_uri_sede!="N/A"){
                $obj_uri=DB::table('sedes')->where('nombre', 'LIKE', '%'.$nombre_uri_sede.'%')->first();
            }


            $educadora_coordinadora=$importData[22];
            $hora_inf_inicial=$importData[23];
            $hora_inf_final=$importData[24];
            $observaciones=$importData[25];
            $terapia=$importData[26];
            $programa=$importData[27];
            $nombres_conductor_paga=$importData[28];
            $cedula_conductor_principal=$importData[29];
            $cedula_conductor_principal=trim($cedula_conductor_principal);

            $nombres_conductor_servicio=$importData[30];
            $telefono_conductor=$importData[31]; //Hacer match

            $persona_pago=$importData[32];
            $cedula_persona_pago=$importData[33];
            $costo=$importData[34];
            $costo=trim($costo);
            $costo=str_replace("$","",$costo);
            $costo=str_replace(".","",$costo);

            if( $costo==""){
                $costo=0;
            }

            $descuento=$importData[35]?$importData[35]:0;
            $descuento=trim($descuento);
            $descuento=str_replace("$","",$descuento);
            $descuento=str_replace(".","",$descuento);
            if($descuento==""){
                $descuento=0;
            }
            $precio_alimentacion=trim($importData[36]);
            if($precio_alimentacion==""){
                $precio_alimentacion=0;
            }
            $total_con_descuento=$importData[37];
            $tarifa_cliente=$importData[38];
            $tarifa_cliente=trim($tarifa_cliente);

            $tarifa_cliente=str_replace("$","",$tarifa_cliente);
            $tarifa_cliente=str_replace(".","",$tarifa_cliente);
            if($tarifa_cliente=="" ){
                $tarifa_cliente=0;
            }

            $str_tipo_anticipo=strtoupper(trim($importData[40]));
            $tipo_anticipo=6;

            if($str_tipo_anticipo=='ANTICIPO'){
                $tipo_anticipo=1;
            }
            if($str_tipo_anticipo=='EMPLEADO'){
                $tipo_anticipo=2;
            }
            if($str_tipo_anticipo=='FACTURA'){
                $tipo_anticipo=3;
            }
            if($str_tipo_anticipo=='NEQUI'){
                $tipo_anticipo=4;
            }
            if($str_tipo_anticipo=='DAVIPLATA'){
                $tipo_anticipo=5;
            }

            $nro_factura=trim($importData[39]);


            $nro_pago=trim($importData[41]);
            $fecha_pago=trim($importData[42]);
            if($fecha_pago!=""){
                $fecha_pago=explode("/",$fecha_pago);
                $fecha_pago=$fecha_pago[2].'-'.$fecha_pago[1].'-'.$fecha_pago[0];
            }
            $banco=trim($importData[43]);
            $valor_banco=trim($importData[44]);
            
            if($valor_banco!=""){
                
                $valor_banco=trim(str_replace("$","",$valor_banco));
                $valor_banco=str_replace(".","",$valor_banco);
                $valor_banco=str_replace(",",".",$valor_banco);
            }else{
                $valor_banco=0;
            }
            
            $saldo=trim($importData[45]);
            $saldo=str_replace("$","",$saldo);
            $saldo=str_replace(".","",$saldo);
            $saldo=str_replace(",",".",$saldo);

            if($saldo==""){
                $saldo=0;
            }
            if($tipo_anticipo==1 && $valor_banco>0){
                $saldo=($costo+$precio_alimentacion)-$valor_banco;
            }
            
            if($tipo_anticipo==2){
                $saldo=0;
            }
            
            $orden_compra=trim($importData[46]);

            $placa=$importData[50];
            $cedula_placa=$importData[51];
            $estado_servicio=trim($importData[52]);
            if($estado_servicio==""){
                $estado_servicio=1;
            }else{
                $estado_servicio=(int) $estado_servicio;
            }

            $exp=explode("-", $cedula_placa);
            
            //$cedula_cond_servicio=$exp[0];

           
            $pasajero=false;
            if($cod_paciente!="" && $cod_paciente>0){
                $pasajero=Pasajero::where('codigo',$cod_paciente)->get()->first();
            }

            if(!$pasajero && $telefono_paciente!=""){
                $exp_telefono_paciente=explode("/", $telefono_paciente);
                $pasajero=Pasajero::where('telefono',$exp_telefono_paciente[0])
                ->orWhere('telefono','LIKE','%'.$exp_telefono_paciente[0].'%')
                ->orWhere('celular',$exp_telefono_paciente[0])
                ->orWhere('celular', 'LIKE', '%'.$exp_telefono_paciente[0].'%')
                ->orWhere('whatsapp',$exp_telefono_paciente[0])
                ->orWhere('whatsapp', 'LIKE', '%'.$exp_telefono_paciente[0].'%')
                ->get()->first();
            }

            //Buscamos el pasajero por los nombres
            if(!$pasajero){

                $dtpasajero= DB::table('pasajeros')->join('direcciones','pasajeros.direccion_id','=','direcciones.id')
                ->where('direcciones.ciudad_id',$ciudad)
                ->whereRaw('CONCAT(nombres," ",apellidos) LIKE "%'.$persona_transportar.'%"')->get()->first();
                
                if(!$dtpasajero){
                    $error=true;
                    throw new \Exception("Error, no se encontró el pasajero ".$persona_transportar." en la ciudad $ciudad, en el sistema ");
                    break;
                }
                else{
                    $pasajero=Pasajero::find($dtpasajero->parent_id);
                }
            }
            
            $id_cliente=null;
            if($nombre_cliente!="" && $nombre_cliente!="N/A"){

                $cliente=Cliente::where('nombres', 'LIKE', '%'.$nombre_cliente.'%')
                                ->orWhere('apellidos', 'LIKE', '%'.$nombre_cliente.'%')
                                ->orWhere('razon_social', 'LIKE', '%'.$nombre_cliente.'%')->get()->first();
                
                if($cliente){
                   $id_cliente=$cliente->id;  
                }else{
                    $error=true;
                    throw new \Exception("Error, no se encontró el cliente ".$nombre_cliente." en el sistema ");
                    break;
                }
               
            }
           
            $cond_pago=Conductor::where('documento',$cedula_persona_pago)->get()->first();
            
            if($nombres_conductor_servicio!=""){

                $cond_serv=Conductor::where('nombres', 'LIKE', '%'.$nombres_conductor_servicio.'%')
                        ->orWhere('apellidos', 'LIKE', '%'.$nombres_conductor_servicio.'%')
                        ->orWhere (DB::raw("CONCAT(`nombres`, ' ', `apellidos`)"), 'LIKE', "%".$nombres_conductor_servicio."%")
                       ->get()->first();
               
            }
            
            if(!$cond_pago){
                $error=true;
                throw new \Exception("Error, El conductor con #$cedula_conductor_principal no se encontró el conductor");
                break;
            }
            if(!$cond_serv){
                $error=true;
                throw new \Exception("Error, no se encontró el conductor que prestará el servicio en el sistema. Teléfono".$telefono_conductor);
                break;
            }
            if($placa!=""){
                $vehiculo=Vehiculo::where('placa',$placa)->get()->first();

                if(!is_object($vehiculo)){
                    throw new \Exception("Error, no se encontró un vehiculo con la placa ".$placa." en el sistema.");

                }
            }else{
               throw new \Exception("Error, La placa es requerida para impotar el servicio.");
                break; 
            }

            $servicio=new Servicio();
            $servicio->id_cliente=$id_cliente;
            $servicio->placa=$placa;
            $servicio->id_conductor_pago=$cond_pago->id;
            $servicio->id_conductor_servicio=$cond_serv->id;

            if($pasajero){
                $servicio->id_pasajero=$pasajero->id;
            }
            if($obj_uri){
                $servicio->uri_sede=$obj_uri->id;
            }


            
            $servicio->fecha_solicitud=$fecha_solicitud;
            $servicio->fecha_servicio=$fecha_prestacion;
            $servicio->hora_recogida=$hora_recogida;    
            $servicio->hora_regreso=$hora_regreso;
            $servicio->semana=$semana;
            $servicio->barrio=$barrio;
            $servicio->origen=$origen;
            $servicio->destino=$destino;
            if($tipo_anticipo){
                $servicio->tipo_anticipo=$tipo_anticipo;
            }
            $servicio->tipo_viaje=$tipo_viaje;
            $servicio->tipo_servicio=$tipo_servicio;
            $servicio->valor_conductor=$costo;
            $servicio->valor_cliente=$tarifa_cliente;
            $servicio->descuento=$descuento;
            $servicio->valor_banco=$valor_banco;
            $servicio->turno=$turno;
            $servicio->kilometros=$kilometros;
            $servicio->tiempo=$tiempo;
            $servicio->observaciones=$observaciones;
            $servicio->hora_infusion_inicial=$hora_inf_inicial;
            $servicio->hora_infusion_final=$hora_inf_final;
            $servicio->educador_coordinador=$coordinador;
            $servicio->terapia=$terapia;
            $servicio->programa=$programa;
            $servicio->estado=$estado_servicio;
            $servicio->saldo=$saldo;
            $servicio->orden_compra=$orden_compra;
            if($nro_factura!=""){
                $servicio->nro_factura=$nro_factura;
            }
            
            if($nro_pago!=""){
                $servicio->nro_pago=$nro_pago;
            }
            if($fecha_pago!=""){
                $servicio->fecha_pago=$fecha_pago;
            }
            if($banco!=""){
                $servicio->banco=$banco;
            }
            
            $servicio->user_id=Auth::user()->id;

            $servicio->save();

            \Session::flash('flash_message','Fila Final Importada!.'.($j+1));
             //Buscamos el anticipo
            if($tipo_anticipo==1){

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
                    $abono->valor=$costo;
                    $abono->save();
               }
            }
            

        
        } catch (\Exception $e) {
                $error=true;
                $message='Error en la fila '.($j+1).',';
                $message.=($e->getMessage());
                break;
        }
        
        }
        if(!$error){
            DB::commit();
            \Session::flash('flash_message','Archivo Importado Exitosamente!.');
        }else{
              DB::rollBack();
               
             \Session::flash('flash_bad_message','Error al tratar de impotar los servicios!.'.$message);
        }

        return redirect()->route('servicios');

    }

    public function new()
    { 
        $dt=new CotizacionDetalle();
        $servicio=false;
        $sedes=Sedes::all();


        return view('servicios.new')->with(['servicio'=>$servicio,'detalle'=>$dt,'cotizacion'=>false,'sedes'=>$sedes]);
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

    public function fuec($id)
    {   
        $servicio=Servicio::find($id);
        $detalle=OrdenServicioDetalle::where('orden_servicio_id',$servicio->id)->first();
        $tipo_servicios=TipoServicios::all();


        setlocale(LC_TIME, 'es_ES');
        $monthNum  = date('m',strtotime($servicio->fecha_servicio));
        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $mes = strtoupper(strftime('%B', $dateObj->getTimestamp()));
        $dia=        date('d',strtotime($servicio->fecha_servicio));
        $year=date('Y',strtotime($servicio->fecha_servicio));

        $documentos=Helper::getDocumentosVehiculo($servicio->vehiculo->placa);
        $documentos_conductor=Helper::getDocumentosConductor($servicio->conductorServicio->id);

        $existe=Fuec::where('servicio_id',$servicio->id)->get()->first();
        
        if($existe){
            $fuec=$existe;
        }else{
            $fuec=new Fuec();
            $fuec->servicio_id=$servicio->id;
            $fuec->user_id=Auth::user()->id;
            $fuec->save();
        }
        $consecutivo=$this->getConsecutivoFuec($fuec,$servicio,$year);
        $data=['servicio'=>$servicio,
                                             'detalle'=>$detalle,
                                             'tipo_servicios'=>$tipo_servicios,
                                              'year'=>$year,
                                              'mes'=>$mes,
                                              'dia'=>$dia,
                                              'documentos'=>$documentos,
                                              'documentos_conductor'=>$documentos_conductor,
                                              'consecutivo'=>$consecutivo,

                                         ];
        $qr=$this->getStrQrFuec($data);
        $data['qr']=$qr;

        return view('servicios.fuec')->with($data);

    }


    public function getStrQrFuec($data){
        $saltol='
        ';

        $str='Codigo: '.$data['consecutivo'].$saltol;
        $str.='Razon Social: Movlife S.A.S'.$saltol;
        $str.='Empresa Nit: Movlife S.A.S'.$saltol;
        $str.='Contrato numero: 0974'.$saltol;
        $str.='Contratante: '.$data['servicio']->cliente->razon_social.$saltol;
        $str.='Contratante Nit: '.$data['servicio']->cliente->documento.$saltol;
        $str.='Objeto Contrato: CONTRATO PARA TRANSPORTE DE USUARIOS DEL SERVICIO DE SALUD'.$saltol;
        $str.='Origen-Destino: Origen: '.$data['servicio']->origen.' / Destino: '.$data['servicio']->destino.' Con retorno a su lugar de origen'.$saltol;

        $str.='Convenio: SPECIAL CAR PLUS TRANSPORTE S.A.S'.$saltol;

        $url='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.urlencode($str).'&choe=UTF-8';
        $url_final=str_replace("%0A++++++++","%0A", $url);
        return $url_final;


    }

    public function getConsecutivoFuec($fuec,$servicio,$year){

        $consecutivo='352020418';
        $consecutivo.=$year;
        $contrato=str_pad($fuec->id,4,'0',STR_PAD_LEFT);
        $consecutivo.=$contrato.$contrato;
        return $consecutivo;
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
            $data=$request->all();
           
            unset($data['_token'],
                $data['is_new'],
                $data['hora_estimada_salida'],
                $data['destino2'],$data['destino3'],$data['destino4'],$data['destino5'],
                $data['horas_adicionales']
            );
            
            $servicio=Servicio::firstOrNew($data);
            /*$servicio->create($request->all());*/
            $servicio->user_id=Auth::user()->id;
            $servicio->save();
            \Session::flash('flash_message','Servicio agregado exitosamente!.');
            
            $this->sendEmail($servicio->id);

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
            
            $this->sendEmail($servicio->id);

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


    public function descargar(Request $request){
        ini_set('max_execution_time', '600');
        $servicios=Servicio::whereIn('estado',array(1,2,3));
        $filtros=$request->session()->get('filtros_servicios');

        if(isset($filtros['estado'])){
            $estado=(int) $filtros['estado'];
            if($estado!="" || $estado>0){
                $servicios->where('estado','=',$estado);
            }
        }
        
        if(isset($filtros['cliente'])){
            $cliente=(int) $filtros['cliente'];
            if($cliente!="" || $cliente>0){
                $servicios->where('id_cliente','=',$cliente);
            }
            
        }
        if(isset($filtros['conductor'])){
            $conductor=(int) $filtros['conductor'];
            if($conductor!="" || $conductor>0){
                $servicios->where('id_conductor_servicio','=',$conductor);
            }
            
        }

        if(isset($filtros['conductor_pago'])){
            $conductor_pago=(int) $filtros['conductor_pago'];
            if($conductor_pago!="" || $conductor_pago>0){
                $servicios->where('id_conductor_pago','=',$conductor_pago);
            }
            
        }else{
             $filtros['conductor_pago']="";
        }

        if(isset($filtros['uri_sede'])){
            $uri_sede=(int) $filtros['uri_sede'];
            if($uri_sede!="" || $uri_sede>0){
                $servicios->where('uri_sede','=',$uri_sede);
            }
            
        }else{
             $filtros['uri_sede']="";
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
            
        }

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $servicios->where('fecha_servicio','>=',$fecha_inicial); 
            }
            
        }

        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $servicios->where('fecha_servicio','<=',$fecha_final); 
            }
            
        }

        $servicios=$servicios->orderBy('fecha_servicio','Asc')->get();

        $tipo_servicios=TipoServicios::all();
        $fecha=date('Y-m-d');
        $filename = 'consolidado-semanal-'.$fecha.'.xls';
        header('Content-type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename='.$filename);
        $tabla=view('servicios.descargar')->with(['servicios'=>$servicios,'tipo_servicios'=>$tipo_servicios])->render();
        echo $tabla;
        exit();
    }

    private function getRepository(){
        return Servicio::paginate(Config::get('global_settings.paginate'));
    }
}
