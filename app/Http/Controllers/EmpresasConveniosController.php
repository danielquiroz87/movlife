<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmpresasConvenios;
use App\Models\User;
use App\Models\Direccion;
use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmpresasConveniosController extends Controller
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
        $empresas=$this->getRepository();
        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                $empresas=EmpresasConvenios::where('nit','LIKE', '%'.$search.'%')
                                          ->orWhere('razon_social', 'LIKE', '%'.$search.'%')
                                          ->orWhere('representante_legal_documento', 'LIKE', '%'.$search.'%')
                                          ->orWhere('representante_legal_nombres', 'LIKE', '%'.$search.'%')
                                          ->orWhere('email_contacto', 'LIKE', '%'.$search.'%')
                                          ->orWhere('celular', 'LIKE', '%'.$search.'%')
                                          ->orWhere('telefono', 'LIKE', '%'.$search.'%')
                                          ->orWhere('whatsapp', 'LIKE', '%'.$search.'%');


               $empresas=$empresas->paginate(Config::get('global_settings.paginate'));                           
            }
        }


        return view('empresas.index')->with(['empresas'=>$empresas,'q'=>$q]);
    }
    public function new()
    { 
        return view('empresas.new');
    }
     public function edit($id)
    {   
        $empresa=EmpresasConvenios::find($id);
        $direccion=Direccion::where('tipo_usuario',7)->where('parent_id',$empresa->id)->get()->first();
        //$user=User::where('email',$propietario->email_contacto)->get()->first();
       
        return view('empresas.edit')->with(['empresa'=>$empresa,'direccion'=>$direccion]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $empresa=new EmpresasConvenios();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $empresa=EmpresasConvenios::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'nit' => 'required|max:255',
                'razon_social'=>'required|max:255',
                'representante_legal_documento' => 'required|max:255',
                'representante_legal_nombres' => 'required|max:255',
                'celular'=>'required|unique:propietarios,documento|max:20',
                //'celular'=>'required',
                
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
            $direccion->direccion2=$request->get('direccion_detalle');
            $direccion->tipo_usuario=7;
            $direccion->save();


        }else{

            $v = Validator::make($request->all(), [
                'nit' => 'required|max:255',
                'razon_social'=>'required|max:255',
                'representante_legal_documento' => 'required|max:255',
                'representante_legal_nombres' => 'required|max:255',
                'celular'=>'required|unique:propietarios,documento|max:20',
                //'celular'=>'required',
                
                #'email'=>'required|email|max:255',
                #'password'=>'required|max:20',
                #'departamento_id'=>'required',
                #'ciudad_id'=>'required',
                #'direccion'=>'required'

            ]);   

            
            $direccion=Direccion::where('tipo_usuario',7)->where('parent_id',$empresa->id)->get()->first();
            
            if(!$direccion){
                $direccion=new Direccion();
                $direccion->tipo_usuario=7;
            }

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
            
            $empresa->nit=$request->get('nit');
            $empresa->razon_social=$request->get('razon_social',"");
            $empresa->representante_legal_documento=$request->get('representante_legal_documento');
            $empresa->representante_legal_nombres=$request->get('representante_legal_nombres');
            $empresa->celular=$request->get('celular');
            $empresa->direccion_id=$direccion->id;
            $empresa->activo=1;
            
            if($request->has('telefono')){
                $empresa->telefono=$request->get('telefono');
            }
            if($request->has('whatsapp')){
                $empresa->whatsapp=$request->get('whatsapp');
            }
            if($request->get('email')!=""){
                $empresa->email_contacto=$request->get('email');
            }

            $empresa->save();
           
           
         if($is_new){
            $direccion->parent_id=$empresa->id;
            $direccion->save();

            \Session::flash('flash_message','Empresa agregada exitosamente!.');

         }else{

            \Session::flash('flash_message','Empresa actualizada exitosamente!.');

         }

         return redirect()->route('empresas.convenios');
    }
   
    public function update()
    { 
       
    }
    
    
    public function delete($id){
        $empresa=EmpresasConvenios::find($id);
         $empresa->delete();
         \Session::flash('flash_message','Empresa eliminada exitosamente!.');
         return redirect()->route('empresas.convenios');


    }
    private function getRepository(){
        return EmpresasConvenios::paginate(Config::get('global_settings.paginate'));
    }
}
