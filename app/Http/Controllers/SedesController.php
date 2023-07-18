<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sedes;
use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;


class SedesController extends Controller
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
        $sedes=$this->getRepository();

        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                $sedes=Sedes::where('nombre','LIKE', '%'.$search.'%');
                $sedes=$sedes->paginate(Config::get('global_settings.paginate'));                           
            }
        }

        return view('sedes.index')->with(['sedes'=>$sedes,'q'=>$q]);
    }
    public function new()
    { 
        return view('sedes.new');
    }
    public function edit($id)
    {   
        $sede=Sedes::find($id);
        return view('sedes.edit')->with(['sede'=>$sede]);

    }
    public function save(Request $request)
    { 
      
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $sede=new Sedes();
        }else{
            $is_new=false;
            $id=(int) $request->input('id');
            if($id>0){
                $sede=Sedes::find($id);
            }
        }
        $v = Validator::make($request->all(), [
                'nombre' => 'required|max:255',
                'departamento'=>'required',
                'ciudad'=>'required',
                //'direccion'=>'required'

            ]);   
        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $sede->nombre=$request->get('nombre');
        $sede->departamento_id=$request->get('departamento');
        $sede->ciudad_id=$request->get('ciudad');
        $sede->save();

        if($is_new){
            \Session::flash('flash_message','Sede creada exitosamente!.');

        }else{
            \Session::flash('flash_message','Sede actualizada exitosamente!.');
        }

         return redirect()->route('sedes');
    }


    public function update()
    { 
       
    }
    public function delete($id){
        $sede=Sedes::find($id);
        $sede->delete();

        \Session::flash('flash_message','Registro eliminado exitosamente!.');

         return redirect()->route('sedes');


    }
   

    private function getRepository(){
        return Sedes::paginate(Config::get('global_settings.paginate'));
    }
}
