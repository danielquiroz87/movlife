<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper\Helper;

use App\Models\Fuec;
use App\Models\FuecContrato;
use App\Models\Vehiculo;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;

use Illuminate\Support\Facades\Auth;

class FuecController extends Controller
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
        $fuecs=Fuec::where('id','>',0);
        $filtros=$request->get('filtros');
        $q="";
        
        
        if($request->has('q')){
            $q=$request->get('q');
            $fuecs=Fuec::where('placa','LIKE', '%'.$q.'%')->orderBy('created_at', 'Desc');
        }

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $fuecs->where('fecha_inicial','>=',$fecha_inicial); 
            }
            
        }else{
            $filtros['fecha_inicial']=date('Y-m-01');
        }
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $fuecs->where('fecha_inicial','<=',$fecha_final.' 23:59:59'); 
            }
            
        }else{
            $filtros['fecha_final']=date('Y-m-d');
        }

        if(isset($filtros['cliente'])){
            $cliente=$filtros['cliente'];
            if($cliente!=""){
               $fuecs->where('id_cliente','=',$cliente); 
            }
            
        }else{
            $filtros['cliente']="";
        }

        if(isset($filtros['conductor'])){
            $conductor=$filtros['conductor'];
            if($conductor!=""){
               $fuecs->where('id_conductor','=',$conductor)
                ->orWhere('id_conductor_2','=',$conductor)
                ->orWhere('id_conductor_3','=',$conductor)
                ->orWhere('id_conductor_4','=',$conductor);
            }
            
        }else{
            $filtros['conductor']="";
        }

        if(isset($filtros['placa'])){
            $placa=$filtros['placa'];
            if($placa!=""){
               $fuecs->where('placa','=',$placa); 
            }
            
        }else{
            $filtros['placa']="";
        }

        $fuecs=$fuecs->orderBy('created_at','Desc');
        $fuecs=$fuecs->paginate(Config::get('global_settings.paginate'));                           


        return view('fuec.index')->with(['fuecs'=>$fuecs,'q'=>$q,'filtros'=>$filtros]);
    }

    public function new()
    { 
        return view('fuec.new');
    }
     public function edit($id)
    {   
        $fuec=Fuec::find($id);
        $contrato=FuecContrato::where('id_cliente',$fuec->id_cliente)->get()->first();
        $listContratos=FuecContrato::where('id_cliente',$fuec->id_cliente)->get();
        if($fuec->id_contrato_cliente!=""){
            $contrato=FuecContrato::find($fuec->id_contrato_cliente);
        }
        if($contrato){

        }else{
            $contrato=new FuecContrato();
        }
        return view('fuec.edit')->with(['fuec'=>$fuec,'contrato'=>$contrato,'duplicado'=>false,
        'contratos'=>$listContratos
        ]);

    }
    public function duplicar($id)
    {   
        $fuec_original=Fuec::find($id);
        $contrato=FuecContrato::where('id_cliente',$fuec_original->id_cliente)->get()->first();
        if($contrato){

        }else{
            $contrato=new FuecContrato();
        }   
        
        $fuec=new Fuec();
        $fuec->placa=$fuec_original->placa;
        $fuec->id_conductor=$fuec_original->id_conductor;
        //$fuec->id_conductor_2=$fuec_original->id_conductor_2;
        //$fuec->id_conductor_3=$fuec_original->id_conductor_3;
        $fuec->consecutivo=$fuec_original->consecutivo;
        $fuec->id_cliente=$fuec_original->id_cliente;
        $fuec->tipo=$fuec_original->tipo;
        $fuec->fecha_inicial=$fuec_original->fecha_inicial;
        $fuec->fecha_final=$fuec_original->fecha_final;
        $fuec->ruta_id=$fuec_original->ruta_id;
        $fuec->objeto_contrato_id=$fuec_original->objeto_contrato_id;
        $fuec->fecha_inicial="";
        $fuec->fecha_final="";

        $listContratos=FuecContrato::where('id_cliente',$fuec->id_cliente)->get();

        return view('fuec.edit')->with(['fuec'=>$fuec,'contrato'=>$contrato,'duplicado'=>true,'contratos'=>$listContratos]);

    }
    public function delete($id){
        $fuec=Fuec::find($id);
        $fuec->delete();
        \Session::flash('flash_message','Registro eliminado exitosamente!.');
         return redirect()->route('fuec');
    }
    public function save(Request $request)
    { 
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $fuec=new Fuec();
            
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $fuec=Fuec::find($id);
            }
        }

        $conductor_2=$request->get('id_conductor_2',0);
        $conductor_3=$request->get('id_conductor_3',0);

        if(empty($conductor_2)){
            $conductor_2=0;
        }
        if(empty($conductor_3)){
            $conductor_3=0;
        }
        $conductores=array($request->get('id_conductor'),
                            $conductor_2,
                            $conductor_3
                        );

        $placa=$request->get('placa');
        $fecha_final=$request->get('fecha_final');

        $doc_vencidos=Helper::alertaDocumentos($conductores,$placa,$fecha_final);

        $fecha_alerta=date('Y-m-d',strtotime('+10 days',strtotime(date('Y-m-d'))));

        $doc_vencidos_alerta=Helper::alertaDocumentos($conductores,$placa,$fecha_alerta);
        //$total_vencidos=0;
        $total_vencidos=count($doc_vencidos);

        $faltantes=Helper::getDocumentosObligatorios($placa);
        
        if(count($faltantes)>0){

            $flash_message='Alerta: No es posible guardar el registro, algunos documentos aun no se han subido al sistema.<br/>';
            $str_doc="";
            foreach ($faltantes as $key => $doc) {
                $str_doc.='Tipo Documento:'.$doc['nombre'].',<br/>';
            }
            $flash_message.=$str_doc;

             \Session::flash('flash_bad_message',$flash_message);

              return redirect(url()->previous())
                    ->withInput();

        }

        if($total_vencidos>0){
            $flash_message='Alerta: No es posible guardar el registro, algunos documentos se encuentran vencidos.<br/>';
            $str_doc="";
            foreach ($doc_vencidos as $key => $doc) {
                $str_doc.='Tipo Documento:'.$doc->tipo_documento.',Fecha Vencimiento:'.$doc->fecha_final.''.',<br/>';
            }
            $flash_message.=$str_doc;

             \Session::flash('flash_bad_message',$flash_message);

              return redirect(url()->previous())
                    ->withInput();


        }

        if(count($doc_vencidos_alerta)>0){
            $flash_message='Alerta: Algunos documentos del vehiculo: '.$placa.', y o conductor, se encuentran próximos a vencer.<br/><br/>';
            $str_doc="";
            foreach ($doc_vencidos_alerta as $key => $doc) {
                $str_doc.='Tipo Documento:'.$doc->tipo_documento.',Fecha Vencimiento:'.$doc->fecha_final.''.',<br/>';
            }
            $flash_message.=$str_doc;

             \Session::flash('flash_alert_message',$flash_message);
        }

        $contrato=FuecContrato::where('id',$request->get('id_contrato_cliente'))->get()->first();

        if($is_new){
             $fuec=Fuec::firstOrNew($request->all());
            if($contrato){
                $fuec->contrato=$contrato->contrato;
            }
             $fuec->consecutivo=$fuec->id;
             $fuec->creado_por=Auth::user()->name;
             $fuec->user_id=Auth::user()->id;
             $fuec->save();
            \Session::flash('flash_message','Fuec agregado exitosamente!.');

             return redirect()->route('fuec');

        }else{

            $fuec->update($request->all());
            $fuec->consecutivo=$fuec->id;
            $fuec->contrato=$contrato->contrato;
            $fuec->save();
            \Session::flash('flash_message','Fuec actualizado exitosamente!.');

             return redirect()->route('fuec');

        }

    }


    public function descargar($id)
    {   
        date_default_timezone_set("America/Bogota");
        setlocale(LC_TIME, 'es_CO');

        $fuec=Fuec::find($id);
        $monthNum  = date('m',strtotime($fuec->fecha_inicial));
        $monthNum2  = date('m',strtotime($fuec->fecha_final));
        $monthNum3  = date('m',strtotime($fuec->created_at));
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $dateObj2   = \DateTime::createFromFormat('!m', $monthNum2);
        $dateObj3   = \DateTime::createFromFormat('!m', $monthNum3);
        
        $mes_1 = strtoupper($meses[$monthNum-1]);
        $dia_1= date('d',strtotime($fuec->fecha_inicial));
        $year_1=date('Y',strtotime($fuec->fecha_inicial));

        $mes_2 = strtoupper($meses[$monthNum2-1]);
        $dia_2=  date('d',strtotime($fuec->fecha_final));
        $year_2=date('Y',strtotime($fuec->fecha_final));

        $mes_3 =strtoupper($meses[$monthNum3-1]);

        $dia_3= date('d',strtotime($fuec->created_at));
        $year_3=date('Y',strtotime($fuec->created_at));

        $fechas[0]=['mes'=>$mes_1,'dia'=>$dia_1,'year'=>$year_1];
        $fechas[1]=['mes'=>$mes_2,'dia'=>$dia_2,'year'=>$year_2];
        $fechas[2]=['mes'=>$mes_3,'dia'=>$dia_3,'year'=>$year_3];
        $hora=date('h:i a', strtotime($fuec->created_at));

        $documentos=Helper::getDocumentosVehiculo($fuec->placa);
        $documentos_conductor=Helper::getDocumentosConductor($fuec->id_conductor);
        $documentos_conductor2=Helper::getDocumentosConductor($fuec->id_conductor_2);
        $documentos_conductor3=Helper::getDocumentosConductor($fuec->id_conductor_3);
        $documentos_conductor4=Helper::getDocumentosConductor($fuec->id_conductor_4);



        $documentos_conductor=array_merge($documentos_conductor,$documentos_conductor2);
        $documentos_conductor=array_merge($documentos_conductor,$documentos_conductor3);
        $documentos_conductor=array_merge($documentos_conductor,$documentos_conductor4);


        $contrato=FuecContrato::where('id_cliente',$fuec->id_cliente)->where('tipo',$fuec->tipo)->get()->first();

        if($contrato){
            if($fuec->id_contrato_cliente>0){
                $contrato=FuecContrato::find($fuec->id_contrato_cliente);
            }
        }
        else{

            $contrato_new=new FuecContrato();
            $contrato_new->id_cliente=$fuec->id_cliente;
            $contrato_new->responsable_documento=$fuec->cliente->documento;
            $contrato_new->responsable_nombres=$fuec->cliente->nombres.' '.$fuec->cliente->apellidos;
            $contrato_new->responsable_telefono=$fuec->cliente->celular;
            $contrato_new->responsable_direccion=$fuec->cliente->direccion->direccion1;
            $contrato_new->objeto_contrato_id=$fuec->objeto_contrato_id;
            $contrato_new->tipo=$fuec->tipo;
            $contrato_new->save();

            $contrato_new->contrato=$contrato_new->id;
            $contrato_new->save();

            $contrato=$contrato_new;

        }

        $consecutivo=$this->getConsecutivoFuec($fuec,$contrato,$year_3);
        $vehiculo=Vehiculo::where('placa',$fuec->placa)->get()->first();



        $data=['fuec'=>$fuec,  
              'fechas'=>$fechas,
              'hora'=>strtoupper($hora),
              'documentos'=>$documentos,
              'documentos_conductor'=>$documentos_conductor,
              'consecutivo'=>$consecutivo,
              'contrato'=>$contrato,
              'vehiculo'=>$vehiculo

        ];
        
        $qr=$this->getStrQrFuec($data);
        $data['qr']=$qr;

        return view('fuec.descargar')->with($data);

    }


    public function getStrQrFuec($data){
        $saltol='
        ';
        $ruta='Origen: '.$data['fuec']->ruta->origen_destino;
        $str='Codigo: '.$data['consecutivo'].$saltol;
        $str.='Razon Social: Movlife S.A.S'.$saltol;
        $str.='Empresa Nit: Movlife S.A.S'.$saltol;
        $str.='Contrato numero: '.$data['contrato']->contrato.$saltol;
        $str.='Contratante: '.$data['fuec']->cliente->razon_social.$saltol;
        $str.='Contratante Nit: '.$data['fuec']->cliente->documento.$saltol;
        $str.='Objeto Contrato: '.$data['fuec']->objeto_contrato->nombre.$saltol;
        $str.='Origen-Destino: '.$ruta.$saltol;
        $str.='Convenio: '.$data['vehiculo']->empresa_afiliadora.$saltol;
        //$url='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.urlencode($str).'&choe=UTF-8';
        $url='https://qrcode.tec-it.com/API/QRCode?data='.urlencode($str).'&choe=UTF-8';
        $url_final=str_replace("%0A++++++++","%0A", $url);
        return $url_final;


    }

    public function getConsecutivoFuec($fuec,$contrato,$year){

        $consecutivo='352020418';
        $consecutivo.=$year;
        $contrato=str_pad($contrato->contrato,4,'0',STR_PAD_LEFT);
        $str_consecutivo=str_pad($fuec->id,4,'0',STR_PAD_LEFT);
        $consecutivo.=$contrato.$str_consecutivo;
        return $consecutivo;
    }

    public function getContratoCliente($id){

        $contrato=FuecContrato::where('id_cliente',$id)->get();
        return response()->json([
            'data'=>$contrato
        ]);
    }
    

    private function getRepository(){
        return Tarifario::paginate(Config::get('global_settings.paginate'));
    }
}
