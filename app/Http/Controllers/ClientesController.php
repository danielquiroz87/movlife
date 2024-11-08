<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Direccion;
use App\Models\FuecContrato;
use App\Exports\ClientesExport;
use App\Models\Documentos;
use App\Models\TipoDocumentos;
use Illuminate\Http\UploadedFile;

use Config;
use Excel;

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
                                          ->orWhere('razon_social', 'LIKE', '%'.$search.'%')
                                          ->orWhere('email_contacto', 'LIKE', '%'.$search.'%')
                                          ->orWhere('celular', 'LIKE', '%'.$search.'%')
                                          ->orWhere('telefono', 'LIKE', '%'.$search.'%')
                                          ->orWhere('whatsapp', 'LIKE', '%'.$search.'%');


               $clientes=$clientes->paginate(Config::get('global_settings.paginate'));                           
            }
        }

        return view('clientes.index')->with(['clientes'=>$clientes,'q'=>$q]);
    }
    public function new()
    { 
        return view('clientes.new');
    }
    
    public function contrato_fuec(Request $request,$id,$contrato_id=false)
    { 
        $cliente=Cliente::find($id);
        $contrato=new FuecContrato();
        if(!$cliente){
            \Session::flash('flash_message','Cliente no existe!.');
            return redirect()->back();
        }
        $contratos=FuecContrato::where('id_cliente',$id)->get();

        if(count($contratos)>0){
            
        }else{
            $fuec_contrato=new FuecContrato();
            $fuec_contrato->id_cliente=$id;
            $fuec_contrato->save();

            $fuec_contrato->contrato=$fuec_contrato->id;
            $fuec_contrato->save();

        }
        if($contrato_id && $contrato_id>0){
            $contrato=FuecContrato::find($contrato_id);
        }
        return view('clientes.contrato_fuec')->with(['contratos'=>$contratos,'id_cliente'=>$id,'contrato'=>$contrato]);
    }

    public function contrato_fuec_save(Request $request){

        $existe=false;
        $fuec_contrato=false;
        if($request->get('id')>0){
            $existe=FuecContrato::find($request->get('id'));
        }
        if(!$existe){
           $fuec_contrato=new FuecContrato();
        }else{
            $fuec_contrato=$existe;
        }
        
        $fuec_contrato->contrato=$request->get('contrato');
        $fuec_contrato->id_cliente=$request->get('id_cliente');
        $fuec_contrato->objeto_contrato_id=$request->get('objeto_contrato_id');

        $fuec_contrato->responsable_nombres=$request->get('responsable_nombres');
        $fuec_contrato->responsable_documento=$request->get('responsable_documento');
        $fuec_contrato->responsable_telefono=$request->get('responsable_telefono');
        $fuec_contrato->responsable_direccion=$request->get('responsable_direccion');
        $fuec_contrato->save();
        
        \Session::flash('flash_message','Contrato actualizado exitosamente!.');

        return redirect()->route('customers');
    }


    public function edit($id)
    {   
        $cliente=Cliente::find($id);
        $direccion=Direccion::where('tipo_usuario',1)->where('parent_id',$cliente->id)->get()->first();
        $user=User::where('email',$cliente->email_contacto)->get()->first();
        $documentos=Documentos::whereIn('id_tipo_documento',[22,23,26,27,28,29])
        ->where('id_registro',$id)->get();

        $tipo_documentos=TipoDocumentos::where('tipo_usuario',1)->get();
        $arr_documentos=array();

        foreach ($tipo_documentos as $key => $row) {

        $arr_documentos[$row->id]=$row->nombre;    
        }
        return view('clientes.edit')->with(['cliente'=>$cliente,
                                            'direccion'=>$direccion,
                                            'documentos'=>$documentos,
                                            'tipo_documentos'=>$arr_documentos]);

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
                //'email'=>'required|email|max:255',
                //'password'=>'required|max:20',
                'celular'=>'required',
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

            if($request->get('email')!=""){
                
                //Si el password es diferente de vacio lo cambiamos
                if($request->get('password')!=""){
                    
                    $user=User::where('email',$request->get('email'))->get()->first();

                    if(!$user){
                        $user=new User();
                    }
                    
                    $user->name=$request->get('nombres');
                    $user->email=$request->get('email');
                    $user->password=Hash::make($request->get('password'));
                    $user->save();
                    $cliente->user_id=$user->id;

                }
            }
            

            $cliente->documento=$request->get('documento');
            $cliente->nombres=$request->get('nombres');
            $cliente->apellidos=$request->get('apellidos');
            $cliente->razon_social=$request->get('razon_social',"");
            $cliente->email_contacto=$request->get('email');
            $cliente->celular=$request->get('celular');
            $cliente->plazo_pagos=$request->get('plazo_pagos',60);
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

    public function documentossave(Request $request){
        $id=(int) $request->input('id');
        if($id>0){
            $cliente=Cliente::find($id);
        }

         if($request->has('documentos')){
                $documentos=$request->get('documentos');
                $documentos = $request->file('documentos');
                $uploadedFiles=$request->file('documentos');
                //Actualizamos fechas sin subir el documento

                foreach($documentos as $key=>$arrdocumento){
                    $existeDoc=Documentos::where('id_tipo_documento',$key)->where('id_registro',$id)->get()->first();
                    $infodocumento=(object) $arrdocumento;

                    if($existeDoc){
                        $docbd=Documentos::find($existeDoc->id);
                             if($infodocumento){
                                
                                if(isset($infodocumento->fecha_inicial)){
                                    $docbd->fecha_inicial=$infodocumento->fecha_inicial;
                                }
                                if(isset($infodocumento->fecha_final)){
                                    $docbd->fecha_final=$infodocumento->fecha_final;
                                }
                                if(isset($infodocumento->numero)){
                                    $docbd->numero_documento=$infodocumento->numero;
                                }
                                if(isset($infodocumento->nombre)){
                                    $docbd->nombre_entidad=$infodocumento->nombre;
                                }
                                if(isset($infodocumento->extra1)){
                                    $docbd->extra1=$infodocumento->extra1;
                                }
                                $docbd->save();
                            }


                    }
                }
                
                if($uploadedFiles){

                    foreach( $uploadedFiles as $key=>$file){

                            if(isset($documentos[$key])){
                                $infodocumento=(object)$documentos[$key];
                            }else{
                                $infodocumento=false;
                            }
                            $existeDoc=Documentos::where('id_tipo_documento',$key)->where('id_registro',$id)->get()->first();
                            if($existeDoc){
                                $docbd=Documentos::find($existeDoc->id);
                            }else{
                                $docbd=new Documentos();
                            }
                            $docbd->id_tipo_documento=$key;
                            if($infodocumento){
                                if(isset($infodocumento->fecha_inicial)){
                                    $docbd->fecha_inicial=$infodocumento->fecha_inicial;
                                }
                                if(isset($infodocumento->fecha_final)){
                                    $docbd->fecha_final=$infodocumento->fecha_final;
                                }
                                if(isset($infodocumento->numero)){
                                    $docbd->numero_documento=$infodocumento->numero;
                                }
                                if(isset($infodocumento->nombre)){
                                    $docbd->nombre_entidad=$infodocumento->nombre;
                                }
                                if(isset($infodocumento->extra1)){
                                    $docbd->extra1=$infodocumento->extra1;
                                }
                                $docbd->save();
                            }

                            if(isset($file['cara'][1])){
                                $filecara1=($file['cara'][1]);
                                $fileName=$filecara1->getFileName().'.'.$filecara1->getClientOriginalExtension();
                                $filecara1->move(public_path('uploads'), $fileName);
                                $docbd->cara_frontal='uploads/'.$fileName;

                            }
                            if(isset($file['cara'][2])){
                                $filecara2=($file['cara'][2]);
                                $fileName=$filecara2->getFileName().'.'.$filecara2->getClientOriginalExtension();
                                $filecara2->move(public_path('uploads'), $fileName);
                                $docbd->cara_trasera='uploads/'.$fileName;

                            }
                            $docbd->id_registro=$id;
                            $docbd->save();
                            //$file->move(public_path('file'), $fileName);

                    }

                }
         
               
            }

             \Session::flash('flash_message','Cliente actualizado exitosamente!.');

            return redirect()->back();
           
    }

    public function importar(){
        
     return view('clientes.importar');

    }

    public function exportar() 
    {
        return Excel::download(new ClientesExport(), 'clientes_movlife.csv',\Maatwebsite\Excel\Excel::CSV);
    }

    public function delete($id){
         $cliente=Cliente::find($id);
         $cliente->delete();
         \Session::flash('flash_message','Cliente eliminado exitosamente!.');
         return redirect()->route('customers');
    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Cliente::paginate(Config::get('global_settings.paginate'));
    }
}
