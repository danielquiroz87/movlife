<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Helper\Helper;

use App\Models\ConveniosEmpresariales;
use App\Models\Vehiculo;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;

use Illuminate\Support\Facades\Auth;

class ConveniosEmpresarialesController extends Controller
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
        $convenios=ConveniosEmpresariales::where('id','>',0);
        $filtros=$request->get('filtros');
        $q="";
        
        
        if($request->has('q')){
            $q=$request->get('q');
            $convenios=ConveniosEmpresariales::where('placa','LIKE', '%'.$q.'%')->orderBy('created_at', 'Desc');
        }

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $convenios->where('fecha_inicial','>=',$fecha_inicial); 
            }
            
        }else{
            $filtros['fecha_inicial']=date('Y-m-01');
        }
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $convenios->where('fecha_inicial','<=',$fecha_final.' 23:59:59'); 
            }
            
        }else{
            $filtros['fecha_final']=date('Y-m-d');
        }

        if(isset($filtros['empresa'])){
            $empresa=$filtros['empresa'];
            if($empresa!=""){
               $convenios->where('id_empresa','=',$empresa); 
            }
            
        }else{
            $filtros['empresa']="";
        }

        if(isset($filtros['conductor'])){
            $conductor=$filtros['conductor'];
            if($conductor!=""){
               $convenios->where('id_conductor','=',$conductor);
            }
            
        }else{
            $filtros['conductor']="";
        }

        if(isset($filtros['placa'])){
            $placa=$filtros['placa'];
            if($placa!=""){
               $convenios->where('placa','=',$placa); 
            }
            
        }else{
            $filtros['placa']="";
        }
        $convenios=$convenios->orderBy('created_at','Desc');
        $convenios=$convenios->paginate(Config::get('global_settings.paginate'));

        return view('convenios_empresariales.index')->with(['convenios'=>$convenios,'q'=>$q,'filtros'=>$filtros]);
    }

    public function new()
    { 
        return view('convenios_empresariales.new');
    }
     public function edit($id)
    {   
        $convenio=ConveniosEmpresariales::find($id);
        return view('convenios_empresariales.edit')->with(['convenio'=>$convenio]);

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


        return view('fuec.edit')->with(['fuec'=>$fuec,'contrato'=>$contrato,'duplicado'=>true]);

    }
    public function delete($id){
        $convenio=ConveniosEmpresariales::find($id);
        $convenio->delete();
        \Session::flash('flash_message','Registro eliminado exitosamente!.');
         return redirect()->route('convenios');
    }
    public function save(Request $request)
    { 
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $convenio=new ConveniosEmpresariales();
            
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $convenio=ConveniosEmpresariales::find($id);
            }
        }
        $data= $request->all();
        unset($data['_token'],
        $data['is_new']);

        if($is_new){
           
            $convenio=ConveniosEmpresariales::firstOrNew($data);
            $convenio->user_id=Auth::user()->id;
            $convenio->save();
            \Session::flash('flash_message','Convenio Agregado exitosamente!.');

        }else{

            $convenio->update($data);
            $convenio->save();
            \Session::flash('flash_message','Convenio Actualizado exitosamente!.');


        }
        //Guardar Archivo
        $convenio_file = $request->file('convenio_firmado');
       
        $fileName=$convenio_file->getFileName().'.'.$convenio_file->getClientOriginalExtension();
        $ruta='uploads/convenios_empresariales/'.$fileName;
        $convenio_file->move(public_path('uploads/convenios_empresariales/'), $fileName);
        $convenio->convenio_firmado=$ruta;
        $convenio->save();
        
        return redirect()->route('convenios');


    }


    public function descargar($id)
    {   
        
        setlocale(LC_TIME, 'es_ES');
       
        $convenio=ConveniosEmpresariales::find($id);
        $vehiculo=Vehiculo::where('placa',$convenio->placa)->get()->first();
        $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $monthNum  = date('m',strtotime($convenio->fecha_inicial));
        $monthNum2  = date('m',strtotime($convenio->fecha_final));
        
        $mes_1 = ($meses[$monthNum-1]);
        $mes_2 = ($meses[$monthNum2-1]);

        $dia_1= date('d',strtotime($convenio->fecha_inicial));
        $year_1=date('Y',strtotime($convenio->fecha_inicial));
        $dia_2= date('d',strtotime($convenio->fecha_inicial));
        $year_2=date('Y',strtotime($convenio->fecha_inicial));

        $data=['convenio'=>$convenio, 
              'vehiculo'=>$vehiculo,
              'dia1'=>$dia_1,
              'mes1'=>$mes_1,
              'year1'=>$year_1,
              'dia2'=>$dia_2,
              'mes2'=>$mes_2,
              'year2'=>$year_2,

        ];
       
        return view('convenios_empresariales.descargar')->with($data);

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
