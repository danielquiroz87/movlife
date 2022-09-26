<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conductor;
use App\Models\ConductorHojaDeVida;
use Config;
use App\Models\User;
use App\Models\Direccion;
use App\Models\Documentos;
use App\Models\TipoDocumentos;

use Illuminate\Http\UploadedFile;

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
    public function index(Request $request)
    {   
        $conductores=$this->getRepository();
        
        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                $conductores=Conductor::where('documento','LIKE', '%'.$search.'%')
                                          ->orWhere('nombres', 'LIKE', '%'.$search.'%')
                                          ->orWhere('apellidos', 'LIKE', '%'.$search.'%')
                                          ->orWhere('email_contacto', 'LIKE', '%'.$search.'%')
                                          ->orWhere('celular', 'LIKE', '%'.$search.'%')
                                          ->orWhere('telefono', 'LIKE', '%'.$search.'%')
                                          ->orWhere('whatsapp', 'LIKE', '%'.$search.'%');


               $conductores=$conductores->paginate(config::get('global_settings.paginate'));                           
            }
        }


        return view('conductores.index')->with(['conductores'=>$conductores,'q'=>$q]);
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
        $documentos=Documentos::whereIn('id_tipo_documento',[1,2,3,4,5,6,7,16,17,18,19,20])
                                ->where('id_registro',$id)->get();

        $tipo_documentos=TipoDocumentos::where('tipo_usuario',5)->get();
        $arr_documentos=array();

        foreach ($tipo_documentos as $key => $row) {
            
            $arr_documentos[$row->id]=$row->nombre;    
        }
        return view('conductores.edit')->with(['conductor'=>$conductor,
                                                'direccion'=>$direccion,
                                                'documentos'=>$documentos,
                                                'tipo_documentos'=>$arr_documentos]);

    }
    public function documentossave(Request $request){
        $id=(int) $request->input('id');
        if($id>0){
            $conductor=Conductor::find($id);
        }

         if($request->has('documentos')){
                $documentos=$request->get('documentos');
                $uploadedFiles = $request->file('documentos');
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

             \Session::flash('flash_message','Conductor actualizado exitosamente!.');

            return redirect()->back();
           
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

            if($request->has('lugar_de_nacimiento')){
                $conductor->lugar_de_nacimiento=$request->get('lugar_de_nacimiento');
            }

            if($request->has('lugar_expedicion_documento')){
                $conductor->lugar_expedicion_documento=$request->get('lugar_expedicion_documento');
            }
           
          
            $conductor->save();


           
         if($is_new){

            $direccion->parent_id=$conductor->id;
            $direccion->save();

            $hoja_vida=new ConductorHojaDeVida();
            $hoja_vida->conductor_id=$conductor->id;
            $hoja_vida->save();
            //$user->create($request->all());
            \Session::flash('flash_message','Conductor agregado exitosamente!.');

             return redirect()->route('conductores');

         }else{

            $existe_hoja_vida=ConductorHojaDeVida::where('conductor_id',$conductor->id)->get()->first();
            if($existe_hoja_vida){
                $hoja_vida=$existe_hoja_vida;
            }else{
                $hoja_vida=new ConductorHojaDeVida();
                $hoja_vida->conductor_id=$conductor->id;
            }

            
            $hoja_vida->save();


            \Session::flash('flash_message','Conductor actualizado exitosamente!.');

            return redirect()->back();

         }


    }

    public function hojavidasave(Request $request){

            $id=(int) $request->input('conductor_id');
            if($id>0){
                $conductor=Conductor::find($id);
            }

            $existe_hoja_vida=ConductorHojaDeVida::where('conductor_id',$conductor->id)->get()->first();
            if($existe_hoja_vida){
                $hoja_vida=$existe_hoja_vida;
            }else{
                $hoja_vida=new ConductorHojaDeVida();
                $hoja_vida->conductor_id=$conductor->id;
            }
            $hoja_vida->eps=$request->get('eps')?$request->get('eps'):NULL;
            $hoja_vida->pensiones=$request->get('pensiones')?$request->get('pensiones'):NULL;
            $hoja_vida->arl=$request->get('arl')?$request->get('arl'):NULL;
            $hoja_vida->nivel_riesgo_arl=$request->get('nivel_riesgo_arl')?$request->get('nivel_riesgo_arl'):"";
            
            $hoja_vida->estrato=$request->get('estrato')?$request->get('estrato'):NULL;
            $hoja_vida->numero_hijos=$request->get('numero_hijos')?$request->get('numero_hijos'):NULL;

            $hoja_vida->save();


            \Session::flash('flash_message','Hoja de vida conductor actualizada exitosamente!.');

            return redirect()->back();

    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Conductor::paginate(Config::get('global_settings.paginate'));
    }
}
