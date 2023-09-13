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
        $fuecs=Fuec::orderBy('created_at', 'Desc');
        $fuecs=$fuecs->paginate(Config::get('global_settings.paginate'));
       
        $q="";
        if($request->has('q')){
           $q=$request->get('q');

            $fuecs=Fuec::where('placa','LIKE', '%'.$q.'%')->orderBy('created_at', 'Desc');
            $fuecs=$fuecs->paginate(Config::get('global_settings.paginate'));                           

        }

        return view('fuec.index')->with(['fuecs'=>$fuecs,'q'=>$q]);
    }

    public function new()
    { 
        return view('fuec.new');
    }
     public function edit($id)
    {   
        $fuec=Fuec::find($id);
        $contrato=FuecContrato::where('id_cliente',$fuec->id_cliente)->get()->first();
        if($contrato){

        }else{
            $contrato=new FuecContrato();
        }
        return view('fuec.edit')->with(['fuec'=>$fuec,'contrato'=>$contrato]);

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
        
        $total_vencidos=0;//count($doc_vencidos);

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
        
        if($is_new){
             $fuec=Fuec::firstOrNew($request->all());
             $fuec->consecutivo=$fuec->id;
             $fuec->creado_por=Auth::user()->name;
             $fuec->user_id=Auth::user()->id;
             $fuec->save();
            \Session::flash('flash_message','Fuec agregado exitosamente!.');

             return redirect()->route('fuec');

        }else{

            $fuec->update($request->all());

            \Session::flash('flash_message','Fuec actualizado exitosamente!.');

             return redirect()->route('fuec');

        }

    }


    public function descargar($id)
    {   
        
        setlocale(LC_TIME, 'es_ES');

        $fuec=Fuec::find($id);
        $monthNum  = date('m',strtotime($fuec->fecha_inicial));
        $monthNum2  = date('m',strtotime($fuec->fecha_final));
        $monthNum3  = date('m',strtotime($fuec->created_at));


        $dateObj   = \DateTime::createFromFormat('!m', $monthNum);
        $dateObj2   = \DateTime::createFromFormat('!m', $monthNum2);
        $dateObj3   = \DateTime::createFromFormat('!m', $monthNum3);

        
        $mes_1 = strtoupper(strftime('%B', $dateObj->getTimestamp()));
        $dia_1=        date('d',strtotime($fuec->fecha_inicial));
        $year_1=date('Y',strtotime($fuec->fecha_inicial));

        $mes_2 = strtoupper(strftime('%B', $dateObj2->getTimestamp()));
        $dia_2=        date('d',strtotime($fuec->fecha_final));
        $year_2=date('Y',strtotime($fuec->fecha_final));



        $mes_3 = strtoupper(strftime('%B', $dateObj3->getTimestamp()));
        $dia_3=        date('d',strtotime($fuec->created_at));
        $year_3=date('Y',strtotime($fuec->created_at));


        $fechas[0]=['mes'=>$mes_1,'dia'=>$dia_1,'year'=>$year_1];
        $fechas[1]=['mes'=>$mes_2,'dia'=>$dia_2,'year'=>$year_2];
        $fechas[2]=['mes'=>$mes_3,'dia'=>$dia_3,'year'=>$year_3];
        $hora=date('h:i a', strtotime($fuec->created_at));

        $documentos=Helper::getDocumentosVehiculo($fuec->placa);
        $documentos_conductor=Helper::getDocumentosConductor($fuec->id_conductor);
        $documentos_conductor2=Helper::getDocumentosConductor($fuec->id_conductor_2);
        $documentos_conductor3=Helper::getDocumentosConductor($fuec->id_conductor_3);


        $documentos_conductor=array_merge($documentos_conductor,$documentos_conductor2);
        $documentos_conductor=array_merge($documentos_conductor,$documentos_conductor3);

        
        $contrato=FuecContrato::where('id_cliente',$fuec->id_cliente)->get()->first();
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
        $url='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.urlencode($str).'&choe=UTF-8';
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
    

    private function getRepository(){
        return Tarifario::paginate(Config::get('global_settings.paginate'));
    }
}
