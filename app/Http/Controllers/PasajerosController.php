<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pasajero;
use App\Models\User;
use App\Models\Direccion;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PasajerosController extends Controller
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
        $pasajeros=$this->getRepository();
        return view('pasajeros.index')->with(['pasajeros'=>$pasajeros]);
    }
    public function new()
    { 
        return view('pasajeros.new');
    }
     public function edit($id)
    {   
        $pasajero=Pasajero::find($id);
        $direccion=Direccion::where('tipo_usuario',3)->where('parent_id',$pasajero->id)->get()->first();
        $user=User::where('email',$pasajero->email_contacto)->get()->first();
       
        return view('pasajeros.edit')->with(['pasajero'=>$pasajero,'direccion'=>$direccion]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $user=new User();
            $pasajero=new Pasajero();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $pasajero=Pasajero::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'email'=>'required|email|max:255',
                'celular'=>'required',
                'password'=>'required|max:20',
                'documento'=>'required|max:20',
                'departamento_id'=>'required',
                'ciudad_id'=>'required',
                'direccion'=>'required'

            ]);   

            $direccion=new Direccion();
            $direccion->departamento_id=$request->get('departamento');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            //$direccion->direccion2=$request->get('direccion_detalle');
            $direccion->tipo_usuario=3;
            $direccion->save();
        }else{

            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'documento'=>'required|max:20',
                'direccion'=>'required'
            ]);

            $direccion=Direccion::where('tipo_usuario',3)->where('parent_id',$pasajero->id)->get()->first();
            $direccion->departamento_id=$request->get('departamento');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            $direccion->save();
            $user=User::where('email',$pasajero->email_contacto)->get()->first();

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

            $pasajero->documento=$request->get('documento');

            $pasajero->nombres=$request->get('nombres');
            $pasajero->apellidos=$request->get('apellidos');
            $pasajero->email_contacto=$request->get('email');
            $pasajero->nombre_contacto=$request->get('nombre_contacto');
            $pasajero->telefono_contacto=$request->get('telefono_contacto');
            $pasajero->celular=$request->get('celular');
            $pasajero->user_id=$user->id;
            $pasajero->direccion_id=$direccion->id;
            $pasajero->activo=1;
            
            if($request->has('telefono')){
                $pasajero->telefono=$request->get('telefono');
            }
            if($request->has('whatsapp')){
                $pasajero->whatsapp=$request->get('whatsapp');
            }
            $pasajero->save();
           
           
         if($is_new){

            $direccion->parent_id=$pasajero->id;
            $direccion->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Pasajero agregado exitosamente!.');

             return redirect()->route('pasajeros');

         }else{
            \Session::flash('flash_message','Pasajero actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Pasajero::paginate(25);
    }
}
