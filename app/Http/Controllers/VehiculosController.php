<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\User;
use App\Models\Conductor;

use App\Models\Direccion;
use App\Models\VehiculoConductores;
use App\Models\Documentos;
use App\Models\TipoDocumentos;
use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class VehiculosController extends Controller
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
        
        $vehiculos=Vehiculo::where('id','>', 0);
      
        $q="";
        $arr_ids=[];

        if($request->has('q')){
            if($request->get('q')!=""){

                $search=$request->get('q');
                $q=$search;
                $vehiculos=Vehiculo::where('placa','LIKE', '%'.$search.'%');
               
            }                             
        }
         if(session('is_driver')){

                $conductor=session('driver');
                $vehiculos_ids = DB::table('vehiculos')
                ->join('vehiculos_conductores','vehiculos_conductores.vehiculo_id', '=', 'vehiculos.id')
                ->where('vehiculos_conductores.conductor_id',$conductor->id);
                
                if($request->get('q')!=""){
                    $search=$request->get('q');
                    $vehiculos_ids=$vehiculos_ids->where('placa','LIKE', '%'.$search.'%');
                }
                $vehiculos_ids=$vehiculos_ids->select('vehiculos.id as id')->get();

                foreach ($vehiculos_ids as $key => $id) {
                    ($arr_ids[$id->id]=$id->id);
                }
                $vehiculos=Vehiculo::whereIn('id',$arr_ids);

        }

        $request->session()->put('filtros.vehiculos', ['q'=>$q,'ids'=>$arr_ids]);

        $vehiculos=$vehiculos->paginate(Config::get('global_settings.paginate'));

        return view('vehiculos.index')->with(['vehiculos'=>$vehiculos,'q'=>$q]);
    }
    public function descargarExcel(Request $request){
    
        $filtros=$request->session()->get('filtros.vehiculos');
        $vehiculos=Vehiculo::where('id','>', 0);

        if(isset($filtros['q']) && $filtros['q']!=""){
            $q=$filtros['q'];
            $vehiculos=Vehiculo::where('placa','LIKE', '%'.$q.'%');
        }
        if(isset($filtros['ids']) && count($filtros['ids'])>0){
            $vehiculos=Vehiculo::whereIn('id',$filtros['ids']);
        }
        $fecha=date('Y-m-d H-i');
        $filename = 'vehiculos-por-placas'.$fecha.'.xls';
        $tabla=view('informes.documentos_placa_descargar')->with(['vehiculos'=>$vehiculos->get()]);
        if(isset($_GET['revisar'])){
            var_dump($vehiculos);
        }else{
            header('Content-type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename='.$filename);
        }
        echo $tabla;
        exit();
       
    }

    public function new()
    { 
        return view('vehiculos.new');
    }
     public function edit($id)
    {   
        $vehiculo=Vehiculo::find($id);
        $rowsconductores=VehiculoConductores::where('vehiculo_id','=',$id)->get();
        $arr_conductores=array();
        if($rowsconductores){
            foreach($rowsconductores as $row){
                $conductor=Conductor::find($row->conductor_id);
                $arr_conductores[]=array('id'=>$row->id,'nombre'=>$conductor->nombres.' '.$conductor->apellidos);
            }
        }

        $documentos=Documentos::whereIn('id_tipo_documento',[8,9,10,11,12,13,14,15,17,21,24,25,30])
                                ->where('id_registro',$id)->get();

        $tipo_documentos=TipoDocumentos::where('tipo_usuario',6)->get();
        $arr_documentos=array();

        foreach ($tipo_documentos as $key => $row) {
            
            $arr_documentos[$row->id]=$row->nombre;    
        }
       

        return view('vehiculos.edit')->with(['vehiculo'=>$vehiculo,
                                            'conductores'=>$arr_conductores,
                                            'documentos'=>$documentos,
                                            'tipo_documentos'=>$arr_documentos]);

    }

    public function documentossave(Request $request){
        $id=(int) $request->input('id');
        if($id>0){
            $vehiculo=Vehiculo::find($id);
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
                    }else{
                        $docbd=new Documentos();
                    }
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
                        $docbd->id_tipo_documento=$key;
                        $docbd->id_registro=$id;
                        if($infodocumento->fecha_inicial!="" or $infodocumento->fecha_final!=""){
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
            $vehiculo=new Vehiculo();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $vehiculo=Vehiculo::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'placa' => 'required|max:6|min:6',
                'codigo_interno' => 'required|max:10|min:1',
                'modelo' => 'required|max:4|min:4',
                'color' => 'required|max:20|min:3',
                'pasajeros' => 'required|max:10|min:1',
                'propietario_id' => 'required',
                //'ciudad_id' => 'required|max:2|min:1',
            ]);   

          
        }else{

            $v = Validator::make($request->all(), [
                'placa' => 'required|max:6|min:6',
                'codigo_interno' => 'required|max:10|min:1',
                'modelo' => 'required|max:4|min:4',
                'color' => 'required|max:20|min:3',
                'pasajeros' => 'required|max:10|min:1',
                'propietario_id' => 'required',

                //'departamento_id' => 'required|max:2|min:1',
                //'ciudad_id' => 'required|max:2|min:1',
            ]);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

          
        $vehiculo->placa=$request->get('placa');
        $vehiculo->codigo_interno=$request->get('codigo_interno');
        $vehiculo->modelo=$request->get('modelo');
        $vehiculo->linea=$request->get('linea');
        $vehiculo->id_vehiculo_clase=$request->get('id_vehiculo_clase');
        $vehiculo->id_vehiculo_marca=$request->get('id_vehiculo_marca');
        $vehiculo->id_vehiculo_tipo_combustible=$request->get('id_vehiculo_tipo_combustible');
        $vehiculo->id_vehiculo_uso=$request->get('id_vehiculo_uso');
        $vehiculo->capacidad_pasajeros=$request->get('pasajeros');
        $vehiculo->color=$request->get('color');
        $vehiculo->numero_chasis=$request->get('numero_chasis');
        $vehiculo->numero_motor=$request->get('numero_motor');
        $vehiculo->cilindraje=$request->get('cilindraje');
        $vehiculo->departamento_id=$request->get('departamento_id');
        $vehiculo->ciudad_id=$request->get('ciudad_id');
        $vehiculo->vinculado=$request->get('vinculado')?1:0;
        $vehiculo->convenio_firmado=$request->get('convenio')?1:0;
        $vehiculo->empresa_afiliadora=$request->get('empresa_afiliadora',NULL);
        $vehiculo->propietario_id=$request->get('propietario_id',NULL);
        $vehiculo->fecha_convenio=$request->get('fecha_convenio',NULL);
        
        if($vehiculo->convenio_firmado==0){
           $vehiculo->fecha_convenio=NULL;
        }   
         if($is_new){

           $vehiculo->activo=1; 
           //$vehiculo->propietario_id=1;
           $vehiculo->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Vehiculo agregado exitosamente!.');

             return redirect()->route('vehiculos');

         }else{

             $vehiculo->save();

            \Session::flash('flash_message','Vehiculo actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }

    public function importar(){
        
        return view('vehiculos.importar');

    }

    public function saveConductores(Request $request){

        $conveh=new VehiculoConductores();
        $conveh->vehiculo_id=$request->get('id');
        $vehiculo=Vehiculo::find($conveh->vehiculo_id);

        if($request->has('propietario_id')){
            $vehiculo->propietario_id=$request->get('propietario_id');
            $vehiculo->save();
        }
        if($request->has('conductor_id')){
            $conveh->conductor_id=$request->get('conductor_id');
        }

        $conveh->save();
        \Session::flash('flash_message','Conductores agregados exitosamente!.');
        
        return redirect()->back();

           
    }

    public function deleteConductor($id){
       $conveh=VehiculoConductores::find($id);
       $conveh->delete();
       \Session::flash('flash_message','Conductor eliminado exitosamente!.');
        
        return redirect()->back();
    }
    public function getConductoresPlaca($placa){

        $vehiculo=Vehiculo::where('placa','=',$placa)->get()->first();
        $response="<option value=''>Seleccione Un Conductor</option>";

        if($vehiculo){

            $id=$vehiculo->id;
            $rowsconductores=VehiculoConductores::where('vehiculo_id','=',$id)->get();
            $arr_conductores=array();
            if($rowsconductores){
                foreach($rowsconductores as $row){
                    $conductor=Conductor::find($row->conductor_id);
                    $arr_conductores[]=array('id'=>$conductor->id,'nombres'=>$conductor->nombres.' '.$conductor->apellidos,'documento'=>$conductor->documento);
                }

                foreach ($arr_conductores as $conductor) { 
                    $nombres=$conductor['nombres'].','.$conductor['documento'];
                    $response.='<option value="'.$conductor['id'].'">'.$nombres.'</option>';
                }
            }


        }else{
            $response='<option>No hay conductores</option>';
        }

        return new Response($response);
       
       
    }
     public function delete($id){
         $vehiculo=Vehiculo::find($id);
         $vehiculo->delete();
         \Session::flash('flash_message','Vehiculo eliminado exitosamente!.');
         return redirect()->route('vehiculos');
    }
   
    private function getRepository(){
        return Vehiculo::paginate(Config::get('global_settings.paginate'));
    }
}
