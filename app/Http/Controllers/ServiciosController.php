<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\OrdenServicioDetalle;

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
        $servicio=Servicio::find($id);
        $cotizacion=Cotizacion::find($servicio->cotizacion_id);
        $detalle=OrdenServicioDetalle::where('orden_servicio_id',$servicio->id)->first();
        return view('servicios.edit')->with(['servicio'=>$servicio,'cotizacion'=>$cotizacion,'detalle'=>$detalle]);

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
                $vehiculo=Servicio::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'id_cliente' => 'required',
                'id_pasajero' => 'required',
                'id_conductor' => 'required',
                'fecha_servicio' => 'required',
                'hora_recogida' => 'required',
                'origen' => 'required|max:600|min:3',
                'destino' => 'required|max:600|min:3',
                'tipo_viaje' => 'required',
                'valor_conductor' => 'required',
                'valor_cliente' => 'required',


            ]);   

          
        }else{

            $v = Validator::make($request->all(), [
                'id_cliente' => 'required',
                'id_pasajero' => 'required',
                'id_conductor' => 'required',
                'fecha_servicio' => 'required',
                'hora_recogida' => 'required',
                'origen' => 'required|max:600|min:3',
                'destino' => 'required|max:600|min:3',
                'tipo_viaje' => 'required',
                'valor_conductor' => 'required',
                'valor_cliente' => 'required',
            ]);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

           
         if($is_new){

            $servicio->create($request->all());
            \Session::flash('flash_message','Servicio agregado exitosamente!.');

             return redirect()->route('servicios');

         }else{

            $servicio->update($request->all());

            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

             return redirect()->route('servicios');

         }


    }
   
    public function update()
    { 
       
    }
    private function getRepository(){
        return Servicio::paginate(25);
    }
}
