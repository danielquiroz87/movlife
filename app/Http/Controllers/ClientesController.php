<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Direccion;
use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ClientesController extends Controller
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
    public function logout(){
           //logout user
        auth()->logout();
        // redirect to homepage
        return redirect('/login');
    }
    
    public function index(Request $request)
    {   
        $clientes=$this->getRepository();
        
        $q="";

        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                $clientes=Cliente::where('documento','LIKE', '%'.$search.'%')
                                          ->orWhere('nombres', 'LIKE', '%'.$search.'%')
                                          ->orWhere('apellidos', 'LIKE', '%'.$search.'%')
                                          ->orWhere('email_contacto', 'LIKE', '%'.$search.'%')
                                          ->orWhere('celular', 'LIKE', '%'.$search.'%')
                                          ->orWhere('telefono', 'LIKE', '%'.$search.'%')
                                          ->orWhere('whatsapp', 'LIKE', '%'.$search.'%');


               $clientes=$clientes->paginate(config::get('global_settings.paginate'));                           
            }
        }

        return view('clientes.index')->with(['clientes'=>$clientes,'q'=>$q]);
    }
    public function new()
    { 
        return view('clientes.new');
    }
     public function edit($id)
    {   
        $cliente=Cliente::find($id);
        $direccion=Direccion::where('tipo_usuario',1)->where('parent_id',$cliente->id)->get()->first();
        $user=User::where('email',$cliente->email_contacto)->get()->first();
       
        return view('clientes.edit')->with(['cliente'=>$cliente,'direccion'=>$direccion]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $user=new User();
            $cliente=new Cliente();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $cliente=Cliente::find($id);
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
                'documento'=>'required|unique:clientes,documento|max:20',
                'departamento_id'=>'required',
                'ciudad_id'=>'required',
                'direccion'=>'required'

            ]);   

            $direccion=new Direccion();
            $direccion->departamento_id=$request->get('departamento');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            //$direccion->direccion2=$request->get('direccion_detalle');
            $direccion->tipo_usuario=1;
            $direccion->save();
        }else{

            $v = Validator::make($request->all(), [
                'nombres' => 'required|max:255',
                'apellidos' => 'required|max:255',
                'documento'=>'required|unique:clientes,documento,'.$id.'|max:20',
                'direccion'=>'required'
            ]);

            $direccion=Direccion::where('tipo_usuario',1)->where('parent_id',$cliente->id)->get()->first();
            $direccion->departamento_id=$request->get('departamento_id');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            $direccion->save();
            $user=User::where('email',$cliente->email_contacto)->get()->first();

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

            $cliente->documento=$request->get('documento');
            $cliente->nombres=$request->get('nombres');
            $cliente->apellidos=$request->get('apellidos');
            $cliente->razon_social=$request->get('razon_social',"");
            $cliente->email_contacto=$request->get('email');
            $cliente->celular=$request->get('celular');
            $cliente->user_id=$user->id;
            $cliente->direccion_id=$direccion->id;
            $cliente->activo=1;

            if($request->has('telefono')){
                $cliente->telefono=$request->get('telefono');
            }
            if($request->has('whatsapp')){
                $cliente->whatsapp=$request->get('whatsapp');
            }
          
            $cliente->save();
           
           
         if($is_new){

            $direccion->parent_id=$cliente->id;
            $direccion->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Cliente agregado exitosamente!.');

             return redirect()->route('customers');

         }else{
            \Session::flash('flash_message','Cliente actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Cliente::paginate(25);
    }
}
