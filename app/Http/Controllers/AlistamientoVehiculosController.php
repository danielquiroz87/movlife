<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\Vehiculo;
use App\Models\User;
use App\Models\Conductor;
use App\Models\VehiculoAlistamientoDiario;
use App\Models\VehiculoAlistamientoDiarioDetalle;
use App\Models\Empleado;


use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\DB;


class AlistamientoVehiculosController extends Controller
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
        $alistamientos=VehiculoAlistamientoDiario::where('conductor_id','>',0);
        $fecha_inicial=$request->get('fecha_inicial','2023-09-01');
        $fecha_final=$request->get('fecha_final',date('Y-m-d'));
        $revisado= $request->get('revisado',-1);

        if(session('is_driver')){
            $conductor=session('driver');
            $alistamientos=VehiculoAlistamientoDiario::where('conductor_id',$conductor->id);
        }
        $alistamientos_ids=$alistamientos->where('fecha','>=',$fecha_inicial)->where('fecha','<=',$fecha_final);
        if($revisado>=0){
            $alistamientos_ids=$alistamientos_ids->where('aprobado','=',$revisado);
        }
        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;

                $alistamientos_ids = DB::table('vehiculo_alistamiento_diario')
                ->join('vehiculos','vehiculo_alistamiento_diario.vehiculo_id', '=', 'vehiculos.id')
                ->where('vehiculos.placa','LIKE', '%'.$search.'%')
                ->where('vehiculo_alistamiento_diario.fecha','>=',$fecha_inicial)
                ->where('vehiculo_alistamiento_diario.fecha','<=',$fecha_final);

                if($revisado>=0){
                   $alistamientos_ids=$alistamientos_ids->where('vehiculo_alistamiento_diario.aprobado','=',$revisado);

                }
            }
        }
        
        if(session('is_driver')){
            $conductor=session('driver');
            $alistamientos_ids=$alistamientos_ids->where('conductor_id',$conductor->id);
        }


        $alistamientos_ids=$alistamientos_ids->select('vehiculo_alistamiento_diario.id')->get();

        $arr_ids=array();
        foreach ($alistamientos_ids as $key => $id) {
            $arr_ids[]=$id->id;
        }

        $alistamientos=VehiculoAlistamientoDiario::whereIn('id',$arr_ids)->orderBy('id','Desc');
        
        $alistamientos=$alistamientos->paginate(Config::get('global_settings.paginate'));
        
        
        return view('alistamiento.index')->with(['alistamientos'=>$alistamientos,'q'=>$q,
            'fecha_inicial'=>$fecha_inicial,
            'fecha_final'=>$fecha_final,
            'revisado'=>$revisado
            ]);
    }
    public function new(Request $request,$id)
    { 
        $vehiculo=Vehiculo::find($id);
        $fecha=date('Y-m-d');

        $existe=VehiculoAlistamientoDiario::where('vehiculo_id',$vehiculo->id)
                                            ->where('fecha',$fecha)->get()->first();


        if($existe){

            \Session::flash('flash_bad_message','Ya existe un  Alistamiento para la fecha!.');
            return redirect()->route('alistamiento');

        }

        $fecha_inicio='2023-10-15';
        $str_fecha=strtotime($fecha_inicio);
        $hoy=date('Y-m-d');
        $str_to_hoy=strtotime($hoy);

        while ($str_fecha <= $str_to_hoy) {
             $fecha_w=date('Y-m-d',$str_fecha);
             $str_fecha=strtotime("+1 day", $str_fecha);
             $existe_w=VehiculoAlistamientoDiario::where('vehiculo_id',$vehiculo->id)
                                            ->where('fecha',$fecha_w)->get()->first();
            if($existe_w){
                continue;
            }else{
                $fecha=$fecha_w;
                break;
            }
            
        }
        $items=DB::select('select* from alistamiento_diario_items order by categoria_id,id asc');
        $categorias=array();
        foreach ($items as $key => $item) {
            $categorias[$item->categoria][]=$item;
        }

        $data=['vehiculo'=>$vehiculo,'categorias'=>$categorias,'fecha'=>$fecha];
        return view('alistamiento.new')->with($data);
    }
    public function save(Request $request){
        
        $hoy=date('Y-m-d');
        $vehiculo=Vehiculo::find($request->get('id'));
        $fecha=$request->get('fecha');
        $idsItems=$request->get('items');
        $items=DB::select('select* from alistamiento_diario_items');

        $al=new VehiculoAlistamientoDiario();
        $al->vehiculo_id=$vehiculo->id;
        
        if(session('is_driver')){
            $conductor=session('driver');
            $al->conductor_id=$conductor->id;
        }
        
        $al->fecha=$fecha;
        $al->aprobado=0;
        $al->kilometros=$request->get('kilometros',0);
        $al->observaciones_conductor=$request->get('observaciones_conductor');
        $al->save();

        foreach ($items as $key => $item) {
            $dt=new VehiculoAlistamientoDiarioDetalle();
            $dt->alistamiento_id=$al->id;
            $dt->item_id=$item->id;
            if(isset($idsItems[$item->id])){
                $dt->item_on=1;
            }else{
                $dt->item_on=0;
            }
            $dt->save();
        }
         \Session::flash('flash_message','Formulario Guardado Exitosamente!.');

         if($fecha==$hoy){
            return redirect()->route('alistamiento');
         }
         else{
                return redirect()->back();
         }


    }


    public function save_revision(Request $request){
        
       
        $id=$request->get('id');
        $al=VehiculoAlistamientoDiario::find($id);
       
        $al->aprobado=$request->get('aprobado');
        $al->kilometros=$request->get('kilometros',0);
        $al->observaciones_movlife=$request->get('observaciones_movlife');
        $al->revisado_por=Auth::user()->id;
        $al->save();

      
         \Session::flash('flash_message','Formulario Guardado Exitosamente!.');

        return redirect()->route('alistamiento',['q'=>$al->vehiculo->placa]);


    }

    public function edit(Request $request,$id)
    {   
        
        $al=VehiculoAlistamientoDiario::find($id);
        $items=$this->getAlistamientoItems($id);
        $vehiculo=Vehiculo::find($al->vehiculo_id);
        $categorias=array();
        foreach ($items as $key => $item) {
            $categorias[$item->categoria][]=$item;
        }

        $data=array('vehiculo'=>$vehiculo,'categorias'=>$categorias,'al'=>$al);

        return view('alistamiento.edit')->with($data);
    }


    public function descargar(Request $request,$id)
    {   
        
        $al=VehiculoAlistamientoDiario::find($id);
        $items=$this->getAlistamientoItems($id);
        $vehiculo=Vehiculo::find($al->vehiculo_id);
        $categorias=array();
        foreach ($items as $key => $item) {
            $categorias[$item->categoria][]=$item;
        }
        $empresa_afiliadora='MOVLIFE S.A.S.';
        $nit='901.184.493-5';

        //Es empleado
        $is_employe=Empleado::where('user_id',$al->revisado_por)->get()->first();
        if($is_employe){
            $cedula_revisor=$is_employe->documento;
            $name=$is_employe->nombres.' '.$is_employe->apellidos;
        }
        else{
            if($al->revisado_por>0){
                $user=User::find($al->revisado_por);
                $name=Auth::user()->name;
                $cedula_revisor="";    
            }else{
                $name="Sin Revisión";
                $cedula_revisor="";  
            }
            
        }


        $data=array('vehiculo'=>$vehiculo,'categorias'=>$categorias,'al'=>$al,
                    'empresa'=>$empresa_afiliadora,
                    'nit'=>$nit,
                    'cedula_revisor'=>$cedula_revisor,
                    'nombre_revisor'=>$name
                );

        return view('alistamiento.descargar')->with($data);
    }


    private function getRepository(){
        return VehiculoAlistamientoDiario::paginate(Config::get('global_settings.paginate'));
    }

    private function getAlistamientoItems($id){
        
        $sql="SELECT d.`id` AS id, 
                    d.`item_on` as 'check',
                    c.`nombre` AS 'categoria',
                    a.`nombre` AS 'item',
                   `a`.`categoria_id` AS 'categoria_id'
                FROM `vehiculo_alistamiento_diario_detalle` `d` 
                join `alistamiento_diario_aspectos`  `a` on d.`item_id`=a.`id` 
                join `alistamiento_diario_categorias` `c`  on a.`categoria_id`=c.id
                where d.`alistamiento_id`=$id";



      return DB::select($sql);          



    }
}
