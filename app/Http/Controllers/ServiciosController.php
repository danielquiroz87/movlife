<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;


use App\Models\User;
use App\Models\Direccion;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ServiciosController extends Controller
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
        $servicios=$this->getRepository();
        return view('servicios.index')->with(['servicios'=>$servicios]);
    }
    public function importar(){

        return view('servicios.importar');
 
    }
    public function delete(){

        return view('servicios.importar');
 
    }
    public function new()
    { 
        $dt=new CotizacionDetalle();
        return view('servicios.new')->with(['detalle'=>$dt,'cotizacion'=>false]);
    }

    public function fromAddress($id,Request $request){
        $dt=CotizacionDetalle::find($id);
        $cotizacion=Cotizacion::find($dt->cotizacion_id);
        $data=[
            'detalle'=>$dt,
            'cotizacion'=>$cotizacion
        ];

        return view('servicios.new')->with($data);

    }

     public function edit($id)
    {   
        $vehiculo=Servicio::find($id);
       
        return view('servicios.edit')->with(['servicio'=>$servicio]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $servicio=new Servicio();
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
                'departamento_id' => 'required|max:2|min:1',
                'ciudad_id' => 'required|max:2|min:1',
            ]);   

          
        }else{

            $v = Validator::make($request->all(), [
                'placa' => 'required|max:6|min:6',
                'codigo_interno' => 'required|max:10|min:1',
                'modelo' => 'required|max:4|min:4',
                'color' => 'required|max:20|min:3',
                'cilindraje' => 'required|max:10|min:1',
                'pasajeros' => 'required|max:10|min:1',
                'departamento_id' => 'required|max:2|min:1',
                'ciudad_id' => 'required|max:2|min:1',
            ]);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

          
        

           
         if($is_new){

          

            //$user->create($request->all());
            \Session::flash('flash_message','Servicio agregado exitosamente!.');

             return redirect()->route('conductores');

         }else{

             $vehiculo->save();

            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Servicio::paginate(25);
    }
}
