<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\User;
use App\Models\Conductor;

use App\Models\Direccion;
use App\Models\VehiculoConductores;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
    public function index()
    {   
        $vehiculos=$this->getRepository();
        return view('vehiculos.index')->with(['vehiculos'=>$vehiculos]);
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
        return view('vehiculos.edit')->with(['vehiculo'=>$vehiculo,'conductores'=>$arr_conductores]);

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
                'cilindraje' => 'required|max:10|min:1',
                'pasajeros' => 'required|max:10|min:1',
                //'departamento_id' => 'required|max:2|min:1',
                //'ciudad_id' => 'required|max:2|min:1',
            ]);   

          
        }else{

            $v = Validator::make($request->all(), [
                'placa' => 'required|max:6|min:6',
                'codigo_interno' => 'required|max:10|min:1',
                'modelo' => 'required|max:4|min:4',
                'color' => 'required|max:20|min:3',
                'cilindraje' => 'required|max:10|min:1',
                'pasajeros' => 'required|max:10|min:1',
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
        $vehiculo->capacidad_pasajeros=$request->get('pasajeros');
        $vehiculo->color=$request->get('color');
        $vehiculo->numero_chasis=$request->get('numero_chasis');
        $vehiculo->numero_motor=$request->get('numero_motor');
        $vehiculo->cilindraje=$request->get('cilindraje');
        $vehiculo->departamento_id=$request->get('departamento_id');
        $vehiculo->ciudad_id=$request->get('ciudad_id');
        $vehiculo->vinculado=$request->get('vinculado')?1:0;
        $vehiculo->convenio_firmado=$request->get('convenio')?1:0;

           
         if($is_new){

           $vehiculo->activo=1; 
           $vehiculo->propietario_id=1;
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
    private function getRepository(){
        return Vehiculo::paginate(25);
    }
}
