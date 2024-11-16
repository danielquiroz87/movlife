<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VehiculoMantenimiento;
use App\Models\VehiculoMantenimientoDetalle;

use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;


class VehiculosMantenimientosController extends Controller
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
        $mantenimientos=$this->getRepository();

        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                $mantenimientos=VehiculoMantenimiento::where('placa','LIKE', '%'.$search.'%');
                $mantenimientos=$mantenimientos->paginate(Config::get('global_settings.paginate'));                           
            }
        }

        return view('vehiculos_mantenimientos.index')->with(['mantenimientos'=>$mantenimientos,'q'=>$q]);
    }
    public function new()
    { 
        return view('vehiculos_mantenimientos.new');
    }
    public function edit($id)
    {   
        $mantenimiento=VehiculoMantenimiento::find($id);
        return view('vehiculos_mantenimientos.edit')->with(['mt'=>$mantenimiento]);

    }
    public function save(Request $request)
    { 
      
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $mantenimiento=new VehiculoMantenimiento();
        }else{
            $is_new=false;
            $id=(int) $request->input('id');
            if($id>0){
                $mantenimiento=VehiculoMantenimiento::find($id);
            }
        }
        $v = Validator::make($request->all(), [
                'fecha'=>'required',
                'placa' => 'required|max:255',
                'kilometros'=>'required',
                //'direccion'=>'required'

            ]);   
        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $mantenimiento->fecha=$request->get('fecha');
        $mantenimiento->placa=$request->get('placa');
        $mantenimiento->kilometros=$request->get('kilometros');
        $mantenimiento->save();

        if($is_new){
            \Session::flash('flash_message','Sede creada exitosamente!.');

        }else{
            \Session::flash('flash_message','Sede actualizada exitosamente!.');
        }

         return redirect()->route('vehiculos.mantenimientos');
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
        return VehiculoMantenimiento::paginate(Config::get('global_settings.paginate'));
    }
}
