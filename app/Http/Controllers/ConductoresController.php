<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conductor;
use App\Models\User;
use App\Models\Direccion;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ConductoresController extends Controller
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
    public function index()
    {   
        $conductores=$this->getRepository();
        return view('conductores.index')->with(['conductores'=>$conductores]);
    }
    public function new()
    { 
        return view('conductores.new');
    }
     public function edit($id)
    {   
        $conductor=Conductor::find($id);
        $direccion=Direccion::where('tipo_usuario',5)->where('parent_id',$conductor->id)->get()->first();
        $user=User::where('email',$conductor->email_contacto)->get()->first();
       
        return view('conductores.edit')->with(['conductor'=>$conductor,'direccion'=>$direccion]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $user=new User();
            $conductor=new Conductor();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $conductor=Conductor::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'email'=>'required|email|max:255',
                'celular'=>'required',
                'password'=>'required|max:20',
                'documento'=>'required|unique:conductores,documento|max:20',
                'departamento_id'=>'required',
                'ciudad_id'=>'required',
                'direccion'=>'required'

            ]);   

            $direccion=new Direccion();
            $direccion->departamento_id=$request->get('departamento');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            if($request->has('barrio')){
                $direccion->barrio=$request->get('barrio');
            }
            $direccion->tipo_usuario=5;
            $direccion->save();
        }else{

            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'documento'=>'required|max:20',
                'direccion'=>'required'
            ]);

            $direccion=Direccion::where('tipo_usuario',5)->where('parent_id',$conductor->id)->get()->first();
            $direccion->departamento_id=$request->get('departamento');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            if($request->has('barrio')){
                $direccion->barrio=$request->get('barrio');
            }
            $direccion->save();
            $user=User::where('email',$conductor->email_contacto)->get()->first();

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

          
            $user->name=$request->get('nombres');
            $user->email=$request->get('email');
            //Si el password es diferente de vacio lo cambiamos
            if($request->get('password')!=""){
                $user->password=Hash::make($request->get('password'));
            }

            $user->save();

            $conductor->documento=$request->get('documento');

            $conductor->nombres=$request->get('nombres');
            $conductor->apellidos=$request->get('apellidos');
            $conductor->email_contacto=$request->get('email');
            $conductor->nombre_contacto=$request->get('nombre_contacto');
            $conductor->telefono_contacto=$request->get('telefono_contacto');
           
            $conductor->celular=$request->get('celular');
            $conductor->user_id=$user->id;
            $conductor->direccion_id=$direccion->id;
            $conductor->activo=1;
            
            if($request->has('telefono')){
                $conductor->telefono=$request->get('telefono');
            }
            if($request->has('whatsapp')){
                $conductor->whatsapp=$request->get('whatsapp');
            }

            if($request->has('estado_civil')){
                $conductor->estado_civil=$request->get('estado_civil');
            }
            if($request->has('grupo_sanguineo')){
                $conductor->grupo_sanguineo=$request->get('grupo_sanguineo');
            }
           
            if($request->has('estrato')){
                $conductor->estrato=$request->get('estrato');
            }
            $conductor->save();
           
           
         if($is_new){

            $direccion->parent_id=$conductor->id;
            $direccion->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Conductor agregado exitosamente!.');

             return redirect()->route('conductores');

         }else{
            \Session::flash('flash_message','Conductor actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Conductor::paginate(25);
    }
}
