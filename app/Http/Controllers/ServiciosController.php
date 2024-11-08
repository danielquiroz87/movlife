<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\ServicioImportador;
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
use App\Models\TarifasTipoServicio;
use Illuminate\Support\Facades\Http;


use Config;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Helpers\Helper\Helper;


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

use Twilio\Rest\Client as TwClient;




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

    public function pasajeroListarServicios(Request $request){

        $documento=$request->get('documento');
        $pasajero=Pasajero::where('documento','=',$documento)->get()->first();
        if($pasajero){

            $servicios=Servicio::where('id_pasajero','=',$pasajero->id);
            $servicios->orderBy('fecha_servicio','Asc');
            $servicios->orderBy('hora_recogida','Asc');
            $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
            return view('servicios.serviciospasajeros')->with(['servicios'=>$servicios]);

        }else{
            return view('servicios.serviciospasajeros')->with(['servicios'=>[]]);
           
        }
    }

    public function conductorListarServicios(Request $request){

        $documento=$request->get('documento');
        $conductor=Conductor::where('documento','=',$documento)->get()->first();
        if($conductor){

            $servicios=Servicio::where('id_conductor_servicio','=',$conductor->id);
            $servicios->orderBy('fecha_servicio','Asc');
            $servicios->orderBy('hora_recogida','Asc');
            $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
            return view('servicios.serviciosconductores')->with(['servicios'=>$servicios]);

        }else{
            return view('servicios.serviciosconductores')->with(['servicios'=>[]]);
           
        }
    }

    public function conductorFinalizarServicio(Request $request,$id){
        $servicio=Servicio::find($id);
        $hora=$request->get('hora_final');
        $documento=$request->get('documento');

        $imagen=$request->file('file');
        $filename='img-'.$servicio->id.'.'.$imagen->getClientOriginalExtension();
        $mover=$imagen->move(public_path('uploads/servicios/'), $filename);
        if($mover){
            $servicio->imagen_conductor='uploads/servicios/'.$filename;
            $servicio->hora_final_conductor=$hora;
            $servicio->cedula_final_conductor=$documento;
            $servicio->fecha_final_conductor=date('Y-m-d H:i:s');
            $servicio->estado=3;
            $servicio->save();

            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

        }
         return redirect()->back();
    }


    public function conductorRecogerPasajero(Request $request,$id){

        $servicio=Servicio::find($id);
       if($servicio){
            $servicio->estado=2;
            $servicio->save();
            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

        }
         return redirect()->back();
    }


    public function pasajeroCancelarServicio(Request $request ,$id){

        $servicio=Servicio::find($id);
        $servicio->estado=4;
        $servicio->motivo_cancelacion=3;
        $servicio->save();
        return redirect()->back();

    }



     public function sendEmail($id){

        $servicio=Servicio::find($id);

        $body = view('servicios.email',compact('servicio'))->render();
        
        $mail = new PHPMailer(true);

        try {
        //Server settings
        /*
        $mail->SMTPDebug = false;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'danykyroz@gmail.com';                     //SMTP username
        $mail->Password   = 'uevp blus zygo yols';     
        */

        
        $mail->SMTPDebug = false;  //Enable verbose debug output
        $mail->isSMTP();   //Send using SMTP
        $mail->Host       = 'smtp.office365.com'; //Set the SMTP server to send through
        $mail->SMTPAuth   = true; //Enable SMTP authentication
        $mail->Username   = 'info@movlife.co';  //SMTP username
        $mail->Password   = 'Xuk40954'; 
        
        $mail->SMTPSecure = 'tls'; //Enable implicit TLS encryption
        $mail->Port       = 587;  
        
        //Recipients
        $mail->setFrom('info@movlife.co', 'Movlife');
        if($servicio->pasajero->email_contacto!=""){
            $mail->addAddress($servicio->pasajero->email_contacto, $servicio->pasajero->nombres); 
        }
        if($servicio->cliente->email!=""){
            $mail->addCC($servicio->cliente->email);
        }
        //$mail->addBCC('claudia.florez@movlife.co');
        //$mail->addBCC('danykyroz@gmail.com');
        $mail->addBCC('melissa.gomez@movlife.co');

        $mail->isHTML(true);                                 
        $mail->Subject = 'Nuevo Servicio Movlife #'.$servicio->id;
        $mail->Body    = $body;
     
        $emailEnviado=$mail->send();
        $response='Message has been sent';


        } catch (Exception $e) {
            $response= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

        return $response;
    }
    
    public function sendMessageWhatsapp($servicio){
        
        $celularPasajero=$servicio->pasajero->celular;
        $celularConductor=$servicio->conductorServicio->celular;

        $from="whatsapp:+573123129835";
        $bodysms = view('servicios.messagesms',compact('servicio'))->render();

        
        if($celularPasajero!=""){

            
                $datasms=[
                    'from' => 'MOVLIFE',
                    'to'=>'57'.$celularPasajero,
                    'text'=>$bodysms,
                    'transliteration'=>'COLOMBIAN',
                    'language'=>array('languageCode'=>'ES')
                ];
              
                
                $responseSms = Http::accept('application/json')
                            ->withBasicAuth('MOVLIFE', 'Movlife2024$$')
                            ->withBody(json_encode($datasms),'application/json')
                            ->withHeaders(['Content-Type'=>'application/json'])
                            ->post('https://api2.iatechsas.com/sms/1/text/single');
        



            /*
            $sid = "AC17ab8f2480b36b82150a69d9834f18c7";
            $token = "70b4dd4458db511ab27ed706bae8e1f4";
            $twilio = new TwClient($sid, $token);
            $body = view('servicios.messagewhatsapp',compact('servicio'))->render();

            
            $message = $twilio->messages->create("whatsapp:+57".$celularPasajero, // to
                array(
                  "from" => $from,
                  "body" => $body
                )
              );

            
            if($celularConductor){
                /*
                $messageConductor = $twilio->messages->create("whatsapp:+57".$celularConductor, // to
                array(
                  "from" => $from,
                  "body" => $body
                )
              );
                */

               $datasms=[
                    'from' => 'MOVLIFE',
                    'to'=>'57'.$celularConductor,
                    'text'=>$bodysms,
                    'transliteration'=>'COLOMBIAN',
                    'language'=>array('languageCode'=>'ES')
                ];
               
                
                $responseSms = Http::accept('application/json')
                            ->withBasicAuth('MOVLIFE', 'Movlife2024$$')
                            ->withBody(json_encode($datasms),'application/json')
                            ->withHeaders(['Content-Type'=>'application/json'])
                            ->post('https://api2.iatechsas.com/sms/1/text/single');
        

            }
            if($servicio->educador_coordinador>0){

               $empleado=Empleado::find($servicio->educador_coordinador);
               if($empleado->whatsapp!=""){
                    /*
                    $messageConductor = $twilio->messages->create("whatsapp:+57".$empleado->whatsapp, // to
                    array(
                    "from" => $from,
                    "body" => $body
                    )
                );*/

                   $datasms=[
                    'from' => 'MOVLIFE',
                    'to'=>'57'.$empleado->whatsapp,
                    'text'=>$bodysms,
                    'transliteration'=>'COLOMBIAN',
                    'language'=>array('languageCode'=>'ES')
                ];
               
                
                $responseSms = Http::accept('application/json')
                            ->withBasicAuth('MOVLIFE', 'Movlife2024$$')
                            ->withBody(json_encode($datasms),'application/json')
                            ->withHeaders(['Content-Type'=>'application/json'])
                            ->post('https://api2.iatechsas.com/sms/1/text/single');

               }
            }

          

    }


    public function sendMessagePreservicioWhatsapp($preservicio){
        
        
        $from="whatsapp:+573123129835";
        $sid = "AC17ab8f2480b36b82150a69d9834f18c7";
        $token = "70b4dd4458db511ab27ed706bae8e1f4";
        $twilio = new TwClient($sid, $token);
        $body = view('servicios.message_preservicio_whatsapp',compact('preservicio'))->render();
       
        if($preservicio->educador_coordinador>0){
            $empleado=Empleado::find($preservicio->educador_coordinador);
            if($empleado->whatsapp!=""){
                 $messageConductor = $twilio->messages->create("whatsapp:+57".$empleado->whatsapp, // to
                 array(
                 "from" => $from,
                 "body" => $body
                 )
             );
            }

            $existeNumeroListaSms=DB::table('conductor_numeros_sms')->where('numero',$empleado->whatsapp)->get()->first();
           
            if($existeNumeroListaSms){

                
                $datasms=[
                    'from' => 'MOVLIFE',
                    'to'=>'57'.$empleado->whatsapp,
                    'text'=>$body,
                    'transliteration'=>'COLOMBIAN',
                    'language'=>array('languageCode'=>'ES')
                ];
               /*
                $datasms2=[
                    'from' => 'MOVLIFE',
                    'to'=>'573127633220',
                    'text'=>$bodysms
                ];

                $responseSms2 = Http::accept('application/json')
                ->withBasicAuth('MOVLIFE', 'Movlife2024$$')
                ->withBody(json_encode($datasms2),'application/json')
                ->withHeaders(['Content-Type'=>'application/json'])
                ->post('https://api2.iatechsas.com/sms/1/text/single');

                */
                
                $responseSms = Http::accept('application/json')
                            ->withBasicAuth('MOVLIFE', 'Movlife2024$$')
                            ->withBody(json_encode($datasms),'application/json')
                            ->withHeaders(['Content-Type'=>'application/json'])
                            ->post('https://api2.iatechsas.com/sms/1/text/single');
        
              
               
            }
         }
     

    }

    public function preservicios_placasave(Request $request){

        $id=$request->get('id');
        $placa=$request->get('placa');
        $conductor_pago=$request->get('id_conductor_pago');
        $conductor_servicio=$request->get('id_conductor_servicio');
        $uri_sede=$request->get('uri_sede');

        $preservicio=PreServicio::find($id);

        
        if($preservicio){
            
            if($preservicio->pasajero_id==""){
                $pasajero=Pasajero::where('documento','=',$preservicio->pasajero_documento)->get()->first();
                if($pasajero){
                    $preservicio->pasajero_id=$pasajero->id;
                }else{
                    \Session::flash('flash_bad_message','El pasajero con documento: '.$preservicio->pasajero_documento.', no existe!.');
                    return redirect()->back();
                }
            }
            
            $preservicio->placa=$placa;
            $preservicio->id_conductor_pago=$conductor_pago;
            $preservicio->id_conductor_servicio=$conductor_servicio;
            $preservicio->id_cliente=$request->get('id_cliente');
            $preservicio->uri_sede=$uri_sede;
            $preservicio->estado=2;
            $preservicio->save();
        }else{
            \Session::flash('flash_bad_message','El servicio no existe!.');
            return redirect()->back();
        }

        $existeServicio=Servicio::where('preservicio_id',$preservicio->id)->get()->first();
        if($existeServicio){
            \Session::flash('flash_bad_message','Error al tratar de guardar el servicio!. Id Preservicio Duplicado');
            return redirect()->back();

        }
        
        $servicio=new Servicio();
        $servicio->id_cliente=$preservicio->id_cliente;
        $servicio->fecha_solicitud=$preservicio->fecha_solicitud;
        $servicio->fecha_servicio=$preservicio->fecha_servicio;
        $servicio->hora_recogida=$preservicio->hora_recogida;
        $servicio->hora_regreso=$preservicio->hora_regreso;
        $servicio->id_pasajero=$preservicio->pasajero_id;

        
        $servicio->placa=$preservicio->placa;
        $servicio->id_conductor_pago=$preservicio->id_conductor_pago;
        $servicio->id_conductor_servicio=$preservicio->id_conductor_servicio;

        $servicio->barrio=$preservicio->barrio;
        $servicio->origen=$preservicio->origen;
        $servicio->destino=$preservicio->destino;
        $servicio->tipo_viaje=$preservicio->tipo_viaje;
        $servicio->tipo_servicio=$preservicio->tipo_servicio;
        $servicio->uri_sede=$preservicio->uri_sede;
        $servicio->observaciones=$preservicio->observaciones;
        $servicio->kilometros=$preservicio->kilometros;
        $servicio->tiempo=$preservicio->tiempo;
        $servicio->educador_coordinador=$preservicio->educador_coordinador;
        $servicio->estado=0;
        $servicio->preservicio_id=$preservicio->id;
        $servicio->user_id=Auth::user()->id;

        $existe_tarifa=TarifasTipoServicio::where('cliente_id',$servicio->id_cliente)
                                            ->where('tipo_servicio',$servicio->tipo_servicio)
                                            ->where('uri_sede',$servicio->uri_sede)->get()->first();
        if($existe_tarifa){

        }else{
            $existe_tarifa=TarifasTipoServicio::where('cliente_id',$servicio->id_cliente)
                                            ->where('tipo_servicio',$servicio->tipo_servicio)
                                            ->where('destino',$servicio->destino)->get()->first();
            
       
        }
        if($existe_tarifa){
            $servicio->valor_conductor=$existe_tarifa->valor_conductor;
            $servicio->valor_cliente=$existe_tarifa->valor_cliente;
        }

        $servicio->save();

        $this->sendEmail($servicio->id);
        $this->sendMessageWhatsapp($servicio);

        
        \Session::flash('flash_message','Servicio actualizado exitosamente!.');

        return redirect()->back();


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
       
     
        $sedes=$request->get('filtros_urisede');
        if($sedes!=""){
            $uri_sede=array_values($sedes);
            if($uri_sede!=""){
                $filtros['uri_sede']=$uri_sede;
                $servicios->whereIn('uri_sede',$uri_sede);
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

        if(isset($filtros['cedula'])){
            $cedula=$filtros['cedula'];
            if($cedula!=""){
               $servicios->where('pasajero_documento','=',$cedula); 
            }
            
        }else{
            $filtros['cedula']="";
        }
        
        $pasajero=$request->get('filtros_pasajero');
        if($pasajero!=""){
            $pasajeros=array_values($pasajero);
            $filtros['pasajero']=$pasajero;
            $servicios->whereIn('pasajero_id',$pasajeros);
        }else{
            $filtros['pasajero']="";
        }
        
        $coordinador=$request->get('filtros_coordinador');
        if($coordinador!=""){
            $coordinadores=array_values($coordinador);
            if($coordinadores!=""){
                $filtros['coordinador']=$coordinadores;
                $servicios->whereIn('educador_coordinador',$coordinadores); 
            }
        }else{
            $filtros['coordinador']="";
        }

        if(isset($filtros['id'])){
            $id_servicio=explode(",",$filtros['id']);
            if($id_servicio!="" || count($id_servicio)>0){
                $servicios->whereIn('id',$id_servicio);
            }
        }
        else{
            $filtros['id']="";
        }

        $request->session()->put('filtros_servicios', $filtros);
       
        $servicios->orderBy('fecha_servicio','Asc');
        $servicios->orderBy('hora_recogida','Asc');
        
        $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        //$servicios=$servicios->paginate(2);

        return view('servicios.preservicios_index')->with(['servicios'=>$servicios,'filtros'=>$filtros]);

    }

    public function preservicio(Request $request){
        
        $sedes=Sedes::all();

        return view('servicios.preservicio')->with(['sedes'=>$sedes]);
    }

    public function preservicio_delete($id){
        
        $preservicio=PreServicio::find($id);
        $preservicio->delete();
        \Session::flash('flash_message','PreServicio eliminado exitosamente!.');

        return redirect()->route('preservicios');
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

        $existeServicio=Servicio::where('preservicio_id',$preservicio->id)->get()->first();
        if($existeServicio){
            \Session::flash('flash_bad_message','Error al tratar de guardar el servicio!. Id Preservicio Duplicado');
            return redirect()->back();

        }

        $servicio=new Servicio();
        $servicio->id_cliente=$preservicio->id_cliente;

        if($preservicio->pasajero_id!=""){
            $existePasajero=Pasajero::find($preservicio->pasajero_id);
           
            if($existePasajero){
                if($existePasajero->cliente_id!=""){
                    $servicio->id_cliente=$existePasajero->cliente_id;
                }
            }
        }
        
        $servicio->fecha_solicitud=$preservicio->fecha_solicitud;
        $servicio->fecha_servicio=$preservicio->fecha_servicio;
        $servicio->hora_recogida=$preservicio->hora_recogida;
        $servicio->hora_regreso=$preservicio->hora_regreso;
        $servicio->id_pasajero=$preservicio->pasajero_id;
        $servicio->placa=$preservicio->placa;
        $servicio->id_conductor_pago=$preservicio->id_conductor_pago;
        $servicio->id_conductor_servicio=$preservicio->id_conductor_servicio;

        $servicio->barrio=$preservicio->barrio;
        $servicio->origen=$preservicio->origen;
        $servicio->destino=$preservicio->destino;
        $servicio->tipo_viaje=$preservicio->tipo_viaje;
        $servicio->tipo_servicio=$preservicio->tipo_servicio;
        if($preservicio->uri_sede>1){
            $servicio->uri_sede=$preservicio->uri_sede;
        }else{
            $servicio->uri_sede=null;
        }
        $servicio->observaciones=$preservicio->observaciones;
        $servicio->kilometros=$preservicio->kilometros;
        $servicio->tiempo=$preservicio->tiempo;
        $servicio->educador_coordinador=$preservicio->educador_coordinador;
        $servicio->estado=1;
        $servicio->preservicio_id=$preservicio->id;
        $existe_tarifa=false;

        if($servicio->uri_sede!=""){
            $existe_tarifa=TarifasTipoServicio::where('cliente_id',$servicio->id_cliente)
            ->where('tipo_servicio',$servicio->tipo_servicio)
            ->where('uri_sede',$servicio->uri_sede)->get()->first();
        }
        if($existe_tarifa){
    
        }else{
            $existe_tarifa=TarifasTipoServicio::where('cliente_id',$servicio->id_cliente)
                ->where('tipo_servicio',$servicio->tipo_servicio)
                ->where('destino',$servicio->destino)->get()->first();


        }
        if($existe_tarifa){
            $servicio->valor_conductor=$existe_tarifa->valor_conductor;
            $servicio->valor_cliente=$existe_tarifa->valor_cliente;
        }

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
            /*
            $preservicio->cliente_documento=$request->get('cliente_documento');
            $preservicio->cliente_nombres=$request->get('cliente_nombres');
            $preservicio->cliente_apellidos=$request->get('cliente_apellidos');
            $preservicio->cliente_email=$request->get('cliente_email');
            $preservicio->cliente_celular=$request->get('cliente_celular');
            */
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

            //Creamos el pasajero
            /*
            $pasajero=new Pasajero();
            $pasajero->documento=$preservicio->pasajero_documento;
            $pasajero->nombres=$preservicio->pasajero_nombres;
            $pasajero->apellidos=$preservicio->pasajero_apellidos;
            $pasajero->celular=$preservicio->pasajero_celular;
            $pasajero->telefono=$preservicio->pasajero_celular;
            $pasajero->whatsapp=$preservicio->pasajero_celular;
            $pasajero->email_contacto=$preservicio->pasajero_email;
            $pasajero->activo=1;
            $pasajero->save();
            $preservicio->pasajero_id=$pasajero->id;
            */

       }
       
       
       $preservicio->cliente_documento=$preservicio->pasajero_documento;
       $preservicio->cliente_nombres=$preservicio->pasajero_nombres;
       $preservicio->cliente_apellidos=$preservicio->pasajero_apellidos;
       $preservicio->cliente_email=$preservicio->pasajero_email;
       $preservicio->cliente_celular=$preservicio->pasajero_celular;

       $preservicio->fecha_solicitud=date('Y-m-d H:i:s');
       $preservicio->fecha_servicio=$request->get('fecha_servicio');
       $preservicio->hora_recogida=$request->get('hora_recogida');
       $preservicio->hora_regreso=$request->get('hora_regreso');

       $preservicio->origen=$request->get('origen');
       $preservicio->destino=$request->get('destino');
       $preservicio->tipo_viaje=$request->get('tipo_viaje');
       $preservicio->tipo_servicio=$request->get('tipo_servicio');
       $preservicio->kilometros=$request->get('kilometros');
       $preservicio->tiempo=$request->get('tiempo');
       $preservicio->educador_coordinador=$request->get('educador_coordinador');
      
       $observaciones=trim(preg_replace('/[\r\n|\n|\r]+/', '. ', $request->get('observaciones')));
       $preservicio->observaciones=$observaciones;

       $uri=$request->get('uri');
       if($uri!=""){
         $sede=Sedes::where('nombre','like',"%{$uri}%")->get()->first();
         if($sede){
            $preservicio->uri_sede=$sede->id;
         }
         if($existe_pasajero && $existe_pasajero->uri_sede>0){
            $preservicio->uri_sede=$existe_pasajero->uri_sede;
         }
       }
      
       $preservicio->estado=1;

       $existe_preservicio=PreServicio::where('fecha_servicio',$preservicio->fecha_servicio)
                                        ->where('hora_recogida',$preservicio->hora_recogida)
                                        ->where('tipo_servicio',$preservicio->tipo_servicio)
                                        ->where('uri_sede',$preservicio->uri_sede)
                                        ->where('origen',$preservicio->origen)
                                        ->where('destino',$preservicio->destino)
                                        ->get()->first();
      
        if($existe_preservicio){
            \Session::flash('flash_bad_message','Error al tratar de guardar el servicio!. Servicio Duplicado');

        }else{
            $preservicio->save();
            $this->sendMessagePreservicioWhatsapp($preservicio);
            \Session::flash('flash_message','Servicio enviado exitosamente!.');

        }

       }catch(Exception $ex){
            \Session::flash('flash_bad_message','Error al tratar de guardar el servicio!.');
       }

        return redirect()->route('web.preservicio');

    }


    public function index(Request $request)
    {   
        
        $user_id=Auth::user()->id;
        $is_driver=Conductor::where('user_id',$user_id)->get()->first();
        $filtros=$request->get('filtros');
       
        if(is_object($is_driver)){
            
            $servicios=Servicio::where('id_conductor_servicio','=',$is_driver->id)
            ->orWhere('id_conductor_pago','=',$is_driver->id);
            $servicios->orderBy('id','Desc');
            $servicios->orderBy('fecha_servicio','Desc');
            $servicios->orderBy('hora_recogida','Asc');
            $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
            return view('servicios.serviciosconductores')->with(['servicios'=>$servicios]);

        }else{
            $is_client=Cliente::where('user_id',$user_id)->get()->first();
            
            if($is_client){
                
                $servicios=Servicio::where('id_cliente','=',$is_client->id);
               
                if(isset($filtros['id'])){
                    $id_servicio=explode(",",$filtros['id']);
                    if($id_servicio!="" || count($id_servicio)>0){
                        $servicios->whereIn('id',$id_servicio);
                    }
                }
                else{
                    $filtros['id']="";
                }
                
                $servicios->orderBy('estado','Asc');
                $servicios->orderBy('fecha_servicio','Desc');
                $servicios->orderBy('hora_recogida','Asc');
                $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
                return view('servicios.serviciospasajeros')->with(['servicios'=>$servicios,'filtros'=>$filtros]);

            }
        }

        $servicios=Servicio::whereIn('estado',array(0,1,2,3,4));
        $filtros=$request->get('filtros');
        
        if(isset($filtros['estado'])){
            $estado=(int) $filtros['estado'];
            if($estado!="" || $estado>=0){
                $servicios->where('estado','=',$estado);
            }
        }
        else{
            $filtros['estado']="";
        }
        
        $cliente=$request->get('filtros_cliente');
        if($cliente!=""){
            $clientes=array_values($cliente);
            if($clientes!=""){
                $filtros['cliente']=$clientes;
                $servicios->whereIn('id_cliente',$clientes);
            }
            
        }else{
             $filtros['cliente']="";
        }

        $conductor=$request->get('filtros_conductor');
        if($conductor){
            $conductores=array_values($conductor);
            if($conductores){
                $filtros['conductor']=$conductores;
                $servicios->whereIn('id_conductor_servicio',$conductores);
            }
            
        }else{
             $filtros['conductor']="";
        }
        /*
        if(isset($filtros['conductor_pago'])){
            $conductor_pago=(int) $filtros['conductor_pago'];
            if($conductor_pago!="" || $conductor_pago>0){
                $servicios->where('id_conductor_pago','=',$conductor_pago);
            }
            
        }else{
             $filtros['conductor_pago']="";
        }
        */

        $sedes=$request->get('filtros_urisede');
        if($sedes!=""){
            $uri_sede=array_values($sedes);
            if($uri_sede!=""){
                $filtros['uri_sede']=$uri_sede;
                $servicios->whereIn('uri_sede',$uri_sede);
            }
            
        }else{
             $filtros['uri_sede']="";
        }

        
        $coordinador=$request->get('filtros_coordinador');

        if($coordinador!=""){
            $coordinadores=array_values($coordinador);
            if($coordinadores!=""){
                $filtros['coordinador']=$coordinadores;
                $servicios->whereIn('educador_coordinador',$coordinadores);
            }
            
        }else{
             $filtros['coordinador']="";
        }

        $pasajero=$request->get('filtros_pasajero');
        if($pasajero!=""){
            $pasajeros=array_values($pasajero);
            $filtros['pasajero']=$pasajero;
            $servicios->whereIn('id_pasajero',$pasajeros);
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


        if(isset($filtros['fecha_inicial_creacion'])){
            $fecha_inicial_creacion=$filtros['fecha_inicial_creacion'];
            if($fecha_inicial_creacion!=""){
               $servicios->where('created_at','>=',$fecha_inicial_creacion); 
            }
            
        }else{
            $filtros['fecha_inicial_creacion']="";
        }
        if(isset($filtros['fecha_final_creacion'])){
            $fecha_final_creacion=$filtros['fecha_final_creacion'];
            if($fecha_final_creacion!=""){
               $servicios->where('created_at','<=',$fecha_final_creacion.' 23:59:59'); 
            }
            
        }else{
            $filtros['fecha_final_creacion']="";
        }

        if(isset($filtros['id'])){
            $id_servicio=explode(",",$filtros['id']);
            if($id_servicio!="" || count($id_servicio)>0){
                $servicios->whereIn('id',$id_servicio);
            }
        }
        else{
            $filtros['id']="";
        }

        if(isset($filtros['importadoraux'])){
            $importadoraux=$filtros['importadoraux'];
            if($importadoraux!=""){
                $servicios=Servicio::where('importadoraux',$importadoraux);
            }
        }
        else{
            $filtros['importadoraux']="";
        }

      
        
        $request->session()->put('filtros_servicios', $filtros);
        $servicios->orderBy('fecha_servicio','Asc');
        $servicios->orderBy('hora_recogida','Asc');

        $exportarSoloPlacas=$request->get('exportarplacas',false);

        if($exportarSoloPlacas){

            $servicios=$servicios->orderBy('placa','Asc')->get();
            $arr_vehiculos=array();
            foreach($servicios as $servicio){
                $vehiculo=Vehiculo::where('placa',$servicio->placa)->get()->first();
                $arr_vehiculos[$vehiculo->id]=$vehiculo;
            }
            $fecha=date('Y-m-d');
            $filename="DocumentosPorPlacaDesdeServicios{$fecha}.xls";
            $html=view('informes.documentos_placa_descargar')->with(['vehiculos'=>$arr_vehiculos,'filtros'=>$filtros]);
            header('Content-type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename='.$filename);
            echo $html;
            exit();

        }
        
        
        //$servicios=$servicios->addSelect('ordenes_servicio.id as IdServicio');
        $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        //$servicios=$servicios->paginate(2);

        return view('servicios.index')->with(['servicios'=>$servicios,'filtros'=>$filtros]);
    }
    public function importar(){

        return view('servicios.importar');
 
    }

    public function importadorpreview(Request $request, $auxid){
        $servicios=ServicioImportador::where('importadoraux','=',$auxid);
        $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        return view('servicios.importador_preview')->with(['servicios'=>$servicios,'auxid'=>$auxid]);

    }

    public function importadorenviarlote(Request $request, $auxid){
        $servicios=ServicioImportador::where('importadoraux','=',$auxid);
        $this->saveServicioImportadorAux($auxid);
        \Session::flash('flash_message','Lote importado exitosamente!.');
        return redirect()->route('servicios',['importadoraux'=>$auxid]);
    }


    public function importadoreliminarlote(Request $request, $auxid){
        $servicios=ServicioImportador::where('importadoraux','=',$auxid)->delete();
        \Session::flash('flash_message','Lote eliminado exitosamente!.');
        return redirect()->route('servicios');
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
        $importadoraux=uniqid();

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
            
            $str_tipo_servicio=trim($importData[3]);
            $strcoordinador=trim($importData[4]);
            
            $obj_coordinador=Empleado::where('area_empresa','=',5)
            ->whereRaw('CONCAT(nombres," ",apellidos) LIKE "%'.$strcoordinador.'%"')
            ->orWhere('id',$strcoordinador)->get()->first();

            $coordinador = null;       
           if($obj_coordinador){
            $coordinador=$obj_coordinador->id;
           }
           if((int)$strcoordinador>0){
            $coordinador=$strcoordinador;
           }

            $semana=trim($importData[5]);
            $persona_transportar=trim($importData[6]);
            $telefono_paciente=trim($importData[7]);
            $nombre_cliente=trim($importData[8]);
            $nombre_uri_sede=trim($importData[9]);

            $ciudad=trim($importData[10]);
            $depto=trim($importData[11]);
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
        
            $str_tipo_viaje=trim($importData[20]);
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
            $observaciones_coordinador=trim($importData[48]);
            $observaciones_contabilidad=trim($importData[49]);


            $placa=strtoupper($importData[50]);
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
            $servicio=new ServicioImportador();
            $servicio->importadoraux=$importadoraux;
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


            
            $servicio->fecha_solicitud=date('Y-m-d H:i:s');
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
            $observaciones=trim(preg_replace('/[\r\n|\n|\r]+/', '. ', $observaciones));
          
            $servicio->observaciones=$observaciones;
            $servicio->hora_infusion_inicial=$hora_inf_inicial;
            $servicio->hora_infusion_final=$hora_inf_final;
            $servicio->educador_coordinador=$coordinador;
            $servicio->terapia=$terapia;
            $servicio->programa=$programa;
            $servicio->estado=$estado_servicio;
            $servicio->saldo=$saldo;
            $servicio->orden_compra=$orden_compra;
            $servicio->observaciones_contabilidad=$observaciones_contabilidad;
            $servicio->comentarios=$observaciones_coordinador;
            
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
            \Session::flash('flash_message','Archivo importado exitosamente!.');
            return redirect()->route('servicios.importador.preview',['auxid'=>$importadoraux]);

        }else{
              DB::rollBack();
             \Session::flash('flash_bad_message','Error al tratar de impotar los servicios!.'.$message);
        }

        return redirect()->route('servicios');

    }


    private function saveServicioImportadorAux($importadoraux){

        $existeLote=Servicio::where('importadoraux',$importadoraux)->get()->first();
        if($existeLote){
            return false;
        }
        
        $sql="insert into ordenes_servicio select null as 'id', `id_cliente`, `placa`, `id_conductor_pago`, `id_conductor_servicio`, `id_pasajero`, `fecha_solicitud`, `fecha_servicio`, `hora_recogida`, `hora_regreso`, `semana`, `barrio`, `origen`, `destino`, `tipo_viaje`, `tipo_servicio`, `tiempo_adicional`, `jornada`, `horas_tiempo_adicional`, `valor_conductor`, `valor_cliente`, `turno`, `observaciones`, `comentarios`, `educador_coordinador`, `uri_sede`, `alimentacion`, `estado`, `motivo_cancelacion`, `created_at`, `updated_at`, `cotizacion_id`, `tipo_anticipo`, `kilometros`, `tiempo`, `orden_compra`, `total_anticipos`, `total_abonos`, `fecha_pago`, `banco`, `doc_contable`, `nro_anticipo`, `valor_banco`, `observaciones_contabilidad`, `nro_pago`, `nro_factura`, `saldo`, `descuento`, `user_id`, `hora_infusion_inicial`, `hora_infusion_final`, `terapia`, `programa`, `fecha_final_conductor`, `cedula_final_conductor`, `imagen_conductor`, `hora_final_conductor`, `fecha_final_coordinador`, `preservicio_id`, `auxiliar`, `valor_auxiliar`, `auxiliar2`, `valor_auxiliar2`, `auxiliar3`, `valor_auxiliar3`, `importadoraux`
        from ordenes_servicio_importador where importadoraux='$importadoraux'";
        
        DB::statement ($sql);

        return true;

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
        if(!$servicio){
            \Session::flash('flash_bad_message','Error, el servicio no existe!.');
            return redirect()->route('servicios');
        }
        if($servicio->cotizacion_id>0){
            $cotizacion=Cotizacion::find($servicio->cotizacion_id);
        }
        else{
            $cotizacion=false;
        }
        $detalle=OrdenServicioDetalle::where('orden_servicio_id',$servicio->id)->first();
        $tipo_servicios=TipoServicios::all();
        $sedes=Sedes::all();
        $empleados=Empleado::where('area_empresa','4')->get();
        $empleado=\Session::get('employe');

        return view('servicios.edit')->with(['servicio'=>$servicio,
                                             'cotizacion'=>$cotizacion,
                                             'detalle'=>$detalle,
                                             'tipo_servicios'=>$tipo_servicios,
                                             'sedes'=>$sedes,
                                             'empleados'=>$empleados,
                                             'empleado'=>$empleado
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
                $data['horas_adicionales'],
                $data['url_tarifa_tiposervicio']
            );
            
            $servicio=Servicio::firstOrNew($data);
            $servicio->user_id=Auth::user()->id;
            $servicio->placa=strtoupper($request->input('placa'));
            $observaciones=trim(preg_replace('/[\r\n|\n|\r]+/', '. ', $request->get('observaciones')));
            $servicio->observaciones=$observaciones;

            $existeServicio=Servicio::where('preservicio_id',$servicio->preservicio_id)->get()->first();
            if($existeServicio){
                \Session::flash('flash_bad_message','Error al tratar de guardar el servicio!. Id Preservicio Duplicado');
                return redirect()->back();
    
            }
            $servicio->save();

            if($servicio->preservicio_id>0){
                $preservicio=PreServicio::find($servicio->preservicio_id);
                if($preservicio){
                    $preservicio->estado=2;
                    $preservicio->save();
                }
            }

            \Session::flash('flash_message','Servicio agregado exitosamente!.');
            
            $this->sendEmail($servicio->id);
            $this->sendMessageWhatsapp($servicio);
            return redirect()->route('servicios');

         }else{
            $data=$request->all();
            unset($data['url_tarifa_tiposervicio']);

            $servicio=Servicio::find($request->get('id'));
            $servicio->update($data);
            $servicio->user_id=Auth::user()->id;
            $servicio->placa=strtoupper($request->input('placa'));
            $observaciones=trim(preg_replace('/[\r\n|\n|\r]+/', '. ', $request->get('observaciones')));
            $servicio->observaciones=$observaciones;
            
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
            if($servicio->conductor){
                if($servicio->conductor->tipo_vinculacion==1){
                    $servicio->tipo_anticipo=2;
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


    public function descargar(Request $request){
        ini_set('max_execution_time', '600');
        $servicios=Servicio::whereIn('estado',array(0,1,2,3,4));
       
        $filtros=$request->session()->get('filtros_servicios');
        
        if(isset($filtros['estado'])){
            $estado=(int) $filtros['estado'];
            if($estado!="" || $estado>0){
                $servicios->where('estado','=',$estado);
            }
        }
        else{
            $filtros['estado']="";
        }
        
        $cliente=$filtros['cliente'];
        
        if($cliente!=""){
            $clientes=array_values($cliente);
            if($clientes!=""){
                $servicios->whereIn('id_cliente',$clientes);
            }
            
        }else{
             $filtros['cliente']="";
        }

        $conductor=$filtros['conductor'];
        if($conductor!=""){
            $conductores=array_values($conductor);
            if($conductores){
                $servicios->whereIn('id_conductor_servicio',$conductores);
            }
            
        }else{
             $filtros['conductor']="";
        }
        /*
        if(isset($filtros['conductor_pago'])){
            $conductor_pago=(int) $filtros['conductor_pago'];
            if($conductor_pago!="" || $conductor_pago>0){
                $servicios->where('id_conductor_pago','=',$conductor_pago);
            }
            
        }else{
             $filtros['conductor_pago']="";
        }
        */

        $sedes=$filtros['uri_sede'];
        if($sedes!=""){
            $uri_sede=array_values($sedes);
            if($uri_sede!=""){
                $servicios->whereIn('uri_sede',$uri_sede);
            }
            
        }else{
             $filtros['uri_sede']="";
        }

        
        $coordinador=$filtros['coordinador'];
        
        if($coordinador!=""){
            $coordinadores=array_values($coordinador);
            if($coordinadores!=""){
                $servicios->whereIn('educador_coordinador',$coordinadores);
            }
            
        }else{
             $filtros['coordinador']="";
        }

        $pasajero=$filtros['pasajero'];
        if($pasajero!=""){
            $pasajeros=array_values($pasajero);
            $servicios->whereIn('id_pasajero',$pasajeros);
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


        if(isset($filtros['fecha_inicial_creacion'])){
            $fecha_inicial_creacion=$filtros['fecha_inicial_creacion'];
            if($fecha_inicial_creacion!=""){
               $servicios->where('created_at','>=',$fecha_inicial_creacion); 
            }
            
        }else{
            $filtros['fecha_inicial_creacion']="";
        }
        if(isset($filtros['fecha_final_creacion'])){
            $fecha_final_creacion=$filtros['fecha_final_creacion'];
            if($fecha_final_creacion!=""){
               $servicios->where('created_at','<=',$fecha_final_creacion.' 23:59:59'); 
            }
            
        }else{
            $filtros['fecha_final_creacion']="";
        }

        if(isset($filtros['id']) && $filtros['id']!="" ){
            $id_servicio=explode(",",$filtros['id']);
            if($id_servicio!="" || count($id_servicio)>0){
                $servicios->whereIn('id',$id_servicio);
            }
        }
        else{
            $filtros['id']="";
        }
        
        if(isset($filtros['importadoraux']) && $filtros['importadoraux']!="" ){
            $importadoraux=$filtros['importadoraux'];
            $servicios=Servicio::whereIn('importadoraux',array($importadoraux));
        }

        if($request->has('temporal')){
            $importadoraux=$request->get('importadoraux');
            $servicios=ServicioImportador::whereIn('importadoraux',array($importadoraux));
        }
        $servicios=$servicios->orderBy('fecha_servicio','Asc')->get();
        $tipo_servicios=TipoServicios::all();
        $fecha=date('Y-m-d');
        $filename = 'consolidado-semanal-'.$fecha.'.xls';
        $tabla=view('servicios.descargar')->with(['servicios'=>$servicios,'tipo_servicios'=>$tipo_servicios])->render();
       
        
        if(isset($_GET['revisar'])){

        }else{
            header('Content-type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename='.$filename);
        }
        echo $tabla;
        exit();
    }

    private function getRepository(){
        return Servicio::paginate(Config::get('global_settings.paginate'));
    }
}
