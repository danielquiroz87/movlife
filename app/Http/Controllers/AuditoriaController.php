<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Auditoria;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;


class AuditoriaController extends Controller
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
        $auditoria=Auditoria::paginate(config::get('global_settings.paginate'));
        $q="";
        if($request->has('q')){
           $q=$request->get('q');
        }

        return view('auditoria.index')->with(['auditoria'=>$auditoria,'q'=>$q]);
    }
   
    public function save(Request $request)
    { 
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $tarifario=new Tarifario();
            
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $tarifario=Tarifario::find($id);
            }
        }
       

        if($is_new){
            $tipo=$request->get('tipo_vehiculo');
            $tarifario->create($request->all());
            $tarifario->tipo_vehiculo=$tipo;
            $tarifario->save();
            \Session::flash('flash_message','Tarifario agregado exitosamente!.');

             return redirect()->route('tarifario');

        }else{

            $tarifario->update($request->all());
            $tipo=$request->get('tipo_vehiculo');
            $destino=$request->get('destino');
            $jornada=$request->get('jornada');
            $tarifario->tipo_vehiculo=$tipo;
            $tarifario->destino=$destino;
            $tarifario->jornada=$jornada;
            $tarifario->save();

            \Session::flash('flash_message','Tarifario actualizado exitosamente!.');

             return redirect()->route('tarifario');

        }

    }
    

    private function getRepository(){
        return Tarifario::paginate(Config::get('global_settings.paginate'));
    }
}
