<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PlanillaServicios;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;


class PlanillaServiciosController extends Controller
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
        $planillas=PlanillaServicios::where('aprobada','>=',0);
        $filtros=$request->get('filtros');

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $planillas->where('fecha','>=',$fecha_inicial); 
            }
            
        }else{
            $filtros['fecha_inicial']=date('Y-m-01');
        }
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $planillas->where('fecha','<=',$fecha_final); 
            }
            
        }else{
            $filtros['fecha_final']=date('Y-m-d');
        }

        if(isset($filtros['cliente'])){
            $conductor=$filtros['cliente'];
            if($conductor!=""){
               $planillas->where('cliente_id','=',$conductor); 
            }
            
        }else{
            $filtros['cliente']="";
        }

        if(isset($filtros['uri_sede'])){
            $uri_sede=$filtros['uri_sede'];
            if($uri_sede!=""){
               $planillas->where('uri_sede','=',$uri_sede); 
            }
            
        }else{
            $filtros['uri_sede']="";
        }

        if(isset($filtros['conductor'])){
            $conductor=$filtros['conductor'];
            if($conductor!=""){
               $planillas->where('conductor_id','=',$conductor); 
            }
            
        }else{
            $filtros['conductor']="";
        }
        
        $planillas=$planillas->paginate(Config::get('global_settings.paginate'));                           
    
        $q="";
        
        return view('planilla_servicios.index')->with(['planillas'=>$planillas,'q'=>$q,'filtros'=>$filtros]);

    }
    public function new()
    { 
        return view('planilla_servicios.new');
    }

    public function edit($id)
    {   
        $planilla=PlanillaServicios::find($id);
        return view('planilla_servicios.edit')->with(['planilla'=>$planilla]);

    }
    public function save(Request $request)
    { 
        $is_new=false;
        $user=false;
        $mover=false;

        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $planilla=new PlanillaServicios();
            
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $planilla=PlanillaServicios::find($id);
            }
        }


        $fecha=$request->get('fecha');
        $fechaName=date('Ymd',strtotime($fecha));
        $clienteId=$request->get('cliente_id');
        $conductorId=$request->get('conductor_id');
        $aprobada=$request->get('aprobada');
        $observaciones=$request->get('observaciones','');
        $uri_sede=$request->get('uri_sede','');

        $archivo=$request->file('file');
       if($archivo){
        $filename='planillas-servicios-'.$fechaName.'-'.$clienteId.'.'.$archivo->getClientOriginalExtension();
        $mover=$archivo->move(public_path('uploads/servicios/planillas/'), $filename);
       }
       
        if($is_new){

            if($mover){
                $planilla->file='uploads/servicios/planillas/'.$filename;
                $planilla->fecha=$fecha;
                $planilla->cliente_id=$clienteId;
                $planilla->conductor_id=$conductorId;
                $planilla->aprobada=0;
                $planilla->uri_sede=$uri_sede;
                $planilla->user_id=Auth::user()->id;
                $planilla->save();
    
                \Session::flash('flash_message','Servicio actualizado exitosamente!.');
    
            }
           
            \Session::flash('flash_message','Planilla agregada exitosamente!.');

             return redirect()->route('planillaservicios');

        }else{

            $planilla->fecha=$fecha;
            $planilla->cliente_id=$clienteId;
            $planilla->conductor_id=$conductorId;
            $planilla->aprobada=$aprobada;
            $planilla->uri_sede=$uri_sede;
            $planilla->observaciones=$observaciones;
            $planilla->save();
            \Session::flash('flash_message','Planilla actualizada exitosamente!.');
             return redirect()->route('planillaservicios');

        }

    }


    public function update()
    { 
       
    }
    public function delete($id){
        
        $planilla=PlanillaServicios::find($id);
        $planilla->delete();
        \Session::flash('flash_message','Registro eliminado exitosamente!.');
        return redirect()->back();

    }
   

    private function getRepository(){
        return Tarifario::paginate(Config::get('global_settings.paginate'));
    }
}
