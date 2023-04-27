<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Propietario;
use App\Models\User;
use App\Models\Direccion;
use Config;

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
    public function index(Request $request)
    {   
        $propietarios=$this->getRepository();
        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                $propietarios=Propietario::where('documento','LIKE', '%'.$search.'%')
                                          ->orWhere('nombres', 'LIKE', '%'.$search.'%')
                                          ->orWhere('apellidos', 'LIKE', '%'.$search.'%')
                                          ->orWhere('email_contacto', 'LIKE', '%'.$search.'%')
                                          ->orWhere('celular', 'LIKE', '%'.$search.'%')
                                          ->orWhere('telefono', 'LIKE', '%'.$search.'%')
                                          ->orWhere('whatsapp', 'LIKE', '%'.$search.'%');


               $propietarios=$propietarios->paginate(Config::get('global_settings.paginate'));                           
            }
        }


        return view('propietarios.index')->with(['propietarios'=>$propietarios,'q'=>$q]);
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
                'documento'=>'required|unique:propietarios,documento|max:20',
                'celular'=>'required',
                
                #'email'=>'required|email|max:255',
                #'password'=>'required|max:20',
                #'departamento_id'=>'required',
                #'ciudad_id'=>'required',
                #'direccion'=>'required'

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
                #'direccion'=>'required'
            ]);

            $direccion=Direccion::where('tipo_usuario',4)->where('parent_id',$propietario->id)->get()->first();
            $direccion->departamento_id=$request->get('departamento_id');
            $direccion->ciudad_id=$request->get('ciudad_id');
            $direccion->direccion1=$request->get('direccion');
            $direccion->direccion2=$request->get('direccion_detalle');
            $direccion->save();

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

          
            
            $propietario->documento=$request->get('documento');
            $propietario->nombres=$request->get('nombres');
            $propietario->apellidos=$request->get('apellidos');
            $propietario->razon_social=$request->get('razon_social',"");
            $propietario->celular=$request->get('celular');
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

            //Si el password es diferente de vacio lo cambiamos
            
            if($request->get('email')!=""){

                $propietario->email_contacto=$request->get('email');
                $user->email=$request->get('email');

                $user=new User();
                $user->name=$request->get('nombres');
                if($request->get('password')!=""){

                    $user->password=Hash::make($request->get('password'));
                }

                $user->save();
                $propietario->user_id=$user->id;
                $propietario->save();

            }
            
            //$user->create($request->all());
            \Session::flash('flash_message','Propietario agregado exitosamente!.');

             return redirect()->route('propietarios');

         }else{

            if($propietario->email_contacto!=""){
                $user=User::where('email',$propietario->email_contacto)->get()->first();
            }
            if(!$user && $request->get('email')!=""){
                $user=new User();
                $user->name=$propietario->nombres.' '.$propietario->apellidos;
            }
            if($request->get('email')!=""){
                $user->email=$request->get('email');
            }
            if($request->get('password')!=""){
                $user->password=Hash::make($request->get('password'));
            }
            if($user){
                $propietario->email_contacto=$request->get('email');
                $propietario->save();
                $user->save();
            }

            \Session::flash('flash_message','Propietario actualizado exitosamente!.');

            return redirect()->route('propietarios');
         }


    }
   
    public function update()
    { 
       
    }
    public function importar(){
        
        
    }
    
    public function delete($id){
        $propietario=Propietario::find($id);
         $propietario->delete();
         \Session::flash('flash_message','Propietario eliminado exitosamente!.');
         return redirect()->route('propietarios');


    }
    private function getRepository(){
        return Propietario::paginate(Config::get('global_settings.paginate'));
    }
}
