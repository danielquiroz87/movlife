<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\JornadaConductor;
use App\Models\Conductor;
use Illuminate\Support\Facades\Auth;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;


class JornadaConductoresController extends Controller
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
        $jornada=false;
        $user_id=Auth::user()->id;
   

        $conductor=Conductor::where('user_id',$user_id)->get()->first();
        $is_driver=$request->session()->get('is_driver');
        $placa=false;

        if($conductor || $is_driver){
            $jornada=JornadaConductor::where('conductor_id',$conductor->id)
                                       ->where('inicio_jornada','>=',date('Y-m-d'))
                                       ->get()->first();
            
        }else{
            $jornadas=JornadaConductor::where('inicio_jornada','>=',date('Y-m-d'));
            $jornadas=$jornadas->paginate(Config::get('global_settings.paginate'));                           

            return view('jornada_conductores.lista')->with(['jornadas'=>$jornadas,'q'=>""]);

        }

        return view('jornada_conductores.index')->with(['jornada'=>$jornada,'conductor'=>$conductor,'placa'=>$placa,'q'=>""]);
    }

    public function placa(Request $request,$placa)
    {   
        $jornada=false;
        $user_id=Auth::user()->id;
        $conductor=Conductor::where('user_id',$user_id)->get()->first();

        if($conductor){
            $jornada=JornadaConductor::where('conductor_id',$conductor->id)
                                       ->where('inicio_jornada','>=',date('Y-m-d'))
                                       ->where('placa',$placa)
                                       ->get()->first();
            if($jornada){
                $jornadas[]=$jornada;
            }else{

            }
            $jornadas=false;

        }
        return view('jornada_conductores.lista')->with(['jornadas'=>$jornadas,'conductor'=>$conductor,'placa'=>$placa]);
    }
   
    public function save(Request $request,$tipo)
    { 
        $jornada=false;
        $user_id=Auth::user()->id;
        $conductor=Conductor::where('user_id',$user_id)->get()->first();
        $placa=$request->get('placa');

        $jornada=JornadaConductor::where('conductor_id',$conductor->id)
        ->where('inicio_jornada','>=',date('Y-m-d'))
        ->where('placa',$placa)
        ->get()->first();

        if(!$jornada){
            $jornada=new JornadaConductor();
            $jornada->conductor_id=$conductor->id;
            $jornada->placa=$request->get('placa');
            $jornada->estado=1;

            //Guardamos una sesion de jornada
            session(['jornada_placa' =>$jornada->placa]);

        }
        if($tipo==1){
            $jornada->inicio_jornada=date('Y-m-d H:i:s');
        }elseif($tipo==2){
            $jornada->inicio_servicios=date('Y-m-d H:i:s');
        }
        elseif($tipo==3){
            $jornada->fin_servicios=date('Y-m-d H:i:s');
        }
        else{
            $jornada->fin_jornada=date('Y-m-d H:i:s');
            $jornada->estado=2;
        }
        $jornada->save();
        $jornadas[]=$jornada;
        \Session::flash('flash_message','Jornada actualizada exitosamente!.');
        return view('jornada_conductores.lista')->with(['jornadas'=>$jornadas,'conductor'=>$conductor,'placa'=>$placa]);

    }
    

    private function getRepository(){
        return JornadaConductor::paginate(Config::get('global_settings.paginate'));
    }
}
