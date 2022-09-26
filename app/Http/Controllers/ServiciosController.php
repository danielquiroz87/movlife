<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Models\OrdenServicioDetalle;

use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\TipoServicios;
use App\Models\Sedes;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Direccion;
use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Http\Helpers\Helper\Helper;


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
    public function index(Request $request)
    {   
        $servicios=Servicio::whereIn('estado',array(1,2,3));
        
        if($request->has('estado')){
            $estado=(int) $request->get('estado');
            if($estado!="" || $estado>0){
                $servicios->where('estado','=',$estado);
            }
            
        }
        if($request->has('fecha_inicial')){
            $fecha_inicial=(int) $request->get('fecha_inicial');
            $servicios->where('created_at','>=',$fecha_inicial);
        }
        if($request->has('fecha_final')){
            $fecha_final=(int) $request->get('fecha_final');
            $servicios->where('created_at','<=',$fecha_final);
        }


        $servicios=$servicios->paginate(Config::get('global_settings.paginate'));
        return view('servicios.index')->with(['servicios'=>$servicios]);
    }
    public function importar(){

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
        $tipo_servicios=TipoServicios::all();
        $sedes=Sedes::all();
        $empleados=Empleado::where('area_empresa','5')->get();


        return view('servicios.edit')->with(['servicio'=>$servicio,
                                             'cotizacion'=>$cotizacion,
                                             'detalle'=>$detalle,
                                             'tipo_servicios'=>$tipo_servicios,
                                             'sedes'=>$sedes,
                                             'empleados'=>$empleados
                                         ]);

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
                'id_conductor_pago' => 'required',
                'id_conductor_servicio' => 'required',
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
                'id_conductor_pago' => 'required',
                'id_conductor_servicio' => 'required',
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
            $servicio=Servicio::find($request->get('id'));
            $servicio->update($request->all());

            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

             return redirect()->route('servicios');

         }


    }
   
    public function update()
    { 
       
    }

    public function delete(Request $request){
        
        $servicio=Servicio::find($request->get('id'));

        $servicio->delete();

        \Session::flash('flash_message','Servicio eliminado exitosamente!.');

        return redirect()->back();


    }


    public function descargar(){
        $servicios=$this->getRepository();
        $tipo_servicios=TipoServicios::all();
       
        return view('servicios.descargar')->with(['servicios'=>$servicios,'tipo_servicios'=>$tipo_servicios]);
    }

    private function getRepository(){
        return Servicio::paginate(Config::get('global_settings.paginate'));
    }
}
