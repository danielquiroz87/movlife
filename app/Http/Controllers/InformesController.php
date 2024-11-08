<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnticiposAbonos;
use App\Models\Anticipos;
use App\Models\Vehiculo;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class InformesController extends Controller
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
    public function documentos(Request $request)
    {   
        
       $fecha_inicial=date('Y-m-d');
       $filtros=$request->get('filtros');
       if(isset($filtros['fecha_final'])){
        $fecha_final=$filtros['fecha_final'];
       }else{
        $filtros['fecha_final']="";
       }
       if(isset($filtros['tipo_vencimiento'])){
         $tipo_vencimiento=$filtros['tipo_vencimiento'];
       }else{
        $filtros['tipo_vencimiento']="";
       }
      
       if(empty($filtros['fecha_final'])){
         $fecha_final=date('Y-m-d',strtotime("+60 days", strtotime(date('Y-m-d'))));
       }
       if($filtros['tipo_vencimiento']==1 || $filtros['tipo_vencimiento']=="" ){
            $fecha_inicial="2000-01-01";
            if($fecha_final==""){
                $fecha_final=date('Y-m-d',strtotime("-1 days", strtotime(date('Y-m-d'))));
            }
       }

       $sql_docs=DB::table('reporte_all_documentos')
       ->selectRaw("*,DATEDIFF(fecha_final,CURRENT_DATE) as dias_para_vencimiento ")
       ->where('fecha_final','>=',$fecha_inicial)
       ->where('fecha_final','<=',$fecha_final)
       ->orderBy('dias_para_vencimiento','asc');
        $documentos=$sql_docs->paginate(25);
       
        return view('informes.documentos')->with(['documentos'=>$documentos,'filtros'=>$filtros]);
    }

    public function documentosPlaca(Request $request){

      $filtros=$request->get('filtros');
      $vehiculos=Vehiculo::where('id','>', 0);
      $exportar=$request->get('exportar',false);
     
      if(isset($filtros['tipo_vinculacion']) && $filtros['tipo_vinculacion']!=""){
        $tipo=$filtros['tipo_vinculacion'];
        if($tipo==1){
          $vehiculos=Vehiculo::where('vinculado',1);
        }else{
          if($tipo==806){
            $vehiculos=Vehiculo::where('propietario_id','=', 806);
          }
        }
      }else{
        $vehiculos=Vehiculo::where('propietario_id','=', 806);
        $filtros['tipo_vinculacion']=806;
      }

      if(isset($filtros['placa']) && $filtros['placa']!=""){
        $vehiculos=Vehiculo::where('placa',$filtros['placa']);
      }
      else{
        $filtros['placa']="";
      }
      
      
      $vehiculos=$vehiculos->orderBy('placa','Asc');
      if($exportar){
        $vehiculos=$vehiculos->get();
        $fecha=date('Y-m-d');
        $filename="DocumentosPorPlaca{$fecha}.xls";
        $html=view('informes.documentos_placa_descargar')->with(['vehiculos'=>$vehiculos,'filtros'=>$filtros]);

        header('Content-type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename='.$filename);
        echo $html;
        exit();
      }
      $vehiculos=$vehiculos->paginate(25);
      return view('informes.documentos_placa')->with(['vehiculos'=>$vehiculos,'filtros'=>$filtros]);

       


    }
    

}
