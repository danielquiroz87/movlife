<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Direccion;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmpleadosController extends Controller
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
        $empleados=$this->getRepository();
        return view('empleados.index')->with(['empleados'=>$empleados]);
    }
    public function new()
    { 
        return view('empleados.new');
    }
     public function edit($id)
    {   
        $empleado=Empleado::find($id);
        $direccion=Direccion::where('tipo_usuario',2)->where('parent_id',$empleado->id)->get()->first();
        $user=User::where('email',$empleado->email_contacto)->get()->first();
       
        return view('empleados.edit')->with(['cliente'=>$empleado,'direccion'=>$direccion]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $user=new User();
            $empleado=new Empleado();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $empleado=Empleado::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'email'=>'required|email|max:255',
                'cargo'=>'required',
                'celular'=>'required',
                'password'=>'required|max:20',
                'documento'=>'required|max:20',
                'departamento_id'=>'required',
                'ciudad_id'=>'required',
                'direccion'=>'required'

            ]);   

            $direccion=new Direccion();
            $direccion->departamento_id=$request->get('departamento_id');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            //$direccion->direccion2=$request->get('direccion_detalle');
            $direccion->tipo_usuario=2;
            $direccion->save();
        }else{

            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'documento'=>'required|max:20',
                'direccion'=>'required'
            ]);

            $direccion=Direccion::where('tipo_usuario',2)->where('parent_id',$empleado->id)->get()->first();
            $direccion->departamento_id=$request->get('departamento_id');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            $direccion->save();
            $user=User::where('email',$empleado->email_contacto)->get()->first();

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

            $empleado->documento=$request->get('documento');
            $empleado->nombres=$request->get('nombres');
            $empleado->apellidos=$request->get('apellidos');
            $empleado->email_contacto=$request->get('email');
            $empleado->celular=$request->get('celular');
            $empleado->user_id=$user->id;
            $empleado->direccion_id=$direccion->id;
            $empleado->activo=1;
            
            if($request->has('telefono')){
                $empleado->telefono=$request->get('telefono');
            }
            if($request->has('whatsapp')){
                $empleado->whatsapp=$request->get('whatsapp');
            }
            $empleado->save();
           
           
         if($is_new){

            $direccion->parent_id=$empleado->id;
            $direccion->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Empleado agregado exitosamente!.');

             return redirect()->route('employes');

         }else{
            \Session::flash('flash_message','Empleado actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Empleado::paginate(25);
    }
}
