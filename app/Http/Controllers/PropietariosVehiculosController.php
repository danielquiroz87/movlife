<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Propietario;
use App\Models\User;
use App\Models\Direccion;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PropietariosVehiculosController extends Controller
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
        $propietarios=$this->getRepository();
        return view('propietarios.index')->with(['propietarios'=>$propietarios]);
    }
    public function new()
    { 
        return view('propietarios.new');
    }
     public function edit($id)
    {   
        $propietario=Propietario::find($id);
        $direccion=Direccion::where('tipo_usuario',4)->where('parent_id',$propietario->id)->get()->first();
        $user=User::where('email',$propietario->email_contacto)->get()->first();
       
        return view('propietarios.edit')->with(['propietario'=>$propietario,'direccion'=>$direccion]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $user=new User();
            $propietario=new Propietario();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $propietario=Propietario::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'razon_social'=>'required|max:255',
                'email'=>'required|email|max:255',
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
            $direccion->tipo_usuario=4;
            $direccion->save();
        }else{

            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'documento'=>'required|max:20',
                'direccion'=>'required'
            ]);

            $direccion=Direccion::where('tipo_usuario',4)->where('parent_id',$propietario->id)->get()->first();
            $direccion->departamento_id=$request->get('departamento_id');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            $direccion->save();
            $user=User::where('email',$propietario->email_contacto)->get()->first();

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

            $propietario->documento=$request->get('documento');
            $propietario->nombres=$request->get('nombres');
            $propietario->apellidos=$request->get('apellidos');
            $propietario->razon_social=$request->get('razon_social',"");
            $propietario->email_contacto=$request->get('email');
            $propietario->celular=$request->get('celular');
            $propietario->user_id=$user->id;
            $propietario->direccion_id=$direccion->id;
            $propietario->activo=1;
            
            if($request->has('telefono')){
                $propietario->telefono=$request->get('telefono');
            }
            if($request->has('whatsapp')){
                $propietario->whatsapp=$request->get('whatsapp');
            }
            $propietario->save();
           
           
         if($is_new){

            $direccion->parent_id=$propietario->id;
            $direccion->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Propietario agregado exitosamente!.');

             return redirect()->route('propietarios');

         }else{
            \Session::flash('flash_message','Propietario actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Propietario::paginate(25);
    }
}
