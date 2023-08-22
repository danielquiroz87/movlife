<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnticiposAbonos;
use App\Models\Anticipos;



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
    

}
