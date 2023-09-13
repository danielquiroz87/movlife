<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Rutas;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;


class RutasController extends Controller
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
        $rutas=Rutas::paginate(config::get('global_settings.paginate'));
        $q="";
        if($request->has('q')){
           $q=$request->get('q');
            $rutas=Rutas::where('codigo','LIKE', '%'.$q.'%')
                                          ->orWhere('origen_destino', 'LIKE', '%'.$q.'%');

            $rutas=$rutas->paginate(config::get('global_settings.paginate'));
                      
        }

        return view('rutas.index')->with(['rutas'=>$rutas,'q'=>$q]);
    }
    public function new()
    { 
        $ruta=Rutas::orderBy('id','Desc')->get()->first();
        return view('rutas.new',['ruta'=>$ruta]);
    }

    public function edit($id)
    {   
        $ruta=Rutas::find($id);
        return view('rutas.edit')->with(['ruta'=>$ruta]);

    }
    public function save(Request $request)
    { 
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $tarifario=new Rutas();
            
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $ruta=Rutas::find($id);
            }
        }
       

        if($is_new){
            $ruta= new Rutas();
            $ruta->codigo=$request->get('codigo');
            $ruta->origen_destino=$request->get('origen_destino');

            $ruta->save();
            \Session::flash('flash_message','Ruta creada exitosamente!.');

             return redirect()->route('rutas');

        }else{

            $ruta->update($request->all());
            $ruta->save();

            \Session::flash('flash_message','Ruta actualizada exitosamente!.');

             return redirect()->route('rutas');

        }

    }


    public function update()
    { 
       
    }
    public function delete($id){
        $ruta=Rutas::find($id);
        $ruta->delete();

        \Session::flash('flash_message','Registro eliminado exitosamente!.');

        return redirect()->back();


    }
   

    private function getRepository(){
        return Rutas::paginate(Config::get('global_settings.paginate'));
    }
}
