<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper\Helper;

use App\Models\Fuec;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;


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
        $fuecs=Fuec::paginate(config::get('global_settings.paginate'));
        $q="";
        if($request->has('q')){
           $q=$request->get('q');
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
        return view('fuec.edit')->with(['fuec'=>$fuec]);

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
       

        if($is_new){
             $fuec=Fuec::firstOrNew($request->all());
             $fuec->save();
            \Session::flash('flash_message','Tarifario agregado exitosamente!.');

             return redirect()->route('fuec');

        }else{

            $fuec->update($request->all());

            \Session::flash('flash_message','Tarifario actualizado exitosamente!.');

             return redirect()->route('fuec');

        }

    }


    public function descargar($id)
    {   
        
        setlocale(LC_TIME, 'es_ES');

        $fuec=Fuec::find($id);
        $monthNum  = date('m',strtotime($fuec->fecha_inicial));
        $monthNum2  = date('m',strtotime($fuec->fecha_inicial));
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

       
        $consecutivo=$this->getConsecutivoFuec($fuec,'2018');
        $data=['fuec'=>$fuec,  
              'fechas'=>$fechas,
              'hora'=>strtoupper($hora),
              'documentos'=>$documentos,
              'documentos_conductor'=>$documentos_conductor,
              'consecutivo'=>$consecutivo,

                                         ];
        $qr=$this->getStrQrFuec($data);
        $data['qr']=$qr;

        return view('fuec.descargar')->with($data);

    }


    public function getStrQrFuec($data){
        $saltol='
        ';
        $ruta='Origen: '.$data['fuec']->ruta->getDepartamentoOrigen->nombre.' - '.$data['fuec']->ruta->origen;
        $ruta.=' Destino: '.$data['fuec']->ruta->getDepartamentoDestino->nombre.' - '.$data['fuec']->ruta->destino;
        $ruta.=' Con retorno a su lugar de origen';

        $str='Codigo: '.$data['consecutivo'].$saltol;
        $str.='Razon Social: Movlife S.A.S'.$saltol;
        $str.='Empresa Nit: Movlife S.A.S'.$saltol;
        $str.='Contrato numero: 0974'.$saltol;
        $str.='Contratante: '.$data['fuec']->cliente->razon_social.$saltol;
        $str.='Contratante Nit: '.$data['fuec']->cliente->documento.$saltol;
        $str.='Objeto Contrato: CONTRATO PARA TRANSPORTE DE USUARIOS DEL SERVICIO DE SALUD'.$saltol;
        $str.='Origen-Destino: '.$ruta.$saltol;
        $str.='Convenio: SPECIAL CAR PLUS TRANSPORTE S.A.S'.$saltol;

        $url='https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.urlencode($str).'&choe=UTF-8';
        $url_final=str_replace("%0A++++++++","%0A", $url);
        return $url_final;


    }

    public function getConsecutivoFuec($fuec,$year){

        $consecutivo='352020418';
        $consecutivo.=$year;
        $contrato=str_pad($fuec->contrato,4,'0',STR_PAD_LEFT);
        $str_consecutivo=str_pad($fuec->id,4,'0',STR_PAD_LEFT);
        $consecutivo.=$contrato.$str_consecutivo;
        return $consecutivo;
    }
    

    private function getRepository(){
        return Tarifario::paginate(Config::get('global_settings.paginate'));
    }
}
