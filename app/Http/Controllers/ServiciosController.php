<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Servicio;
use App\Models\OrdenServicioDetalle;
use App\Models\Anticipos;
use App\Models\AnticiposAbonos;


use App\Models\Cotizacion;
use App\Models\CotizacionDetalle;
use App\Models\TipoServicios;
use App\Models\Sedes;
use App\Models\Empleado;
use App\Models\User;
use App\Models\Direccion;
use Config;
use Illuminate\Support\Facades\DB;


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
        $filtros=$request->get('filtros');
        if(isset($filtros['estado'])){
            $estado=(int) $filtros['estado'];
            if($estado!="" || $estado>0){
                $servicios->where('estado','=',$estado);
            }
        }
        else{
            $filtros['estado']="";
        }
        if(isset($filtros['cliente'])){
            $cliente=(int) $filtros['cliente'];
            if($cliente!="" || $cliente>0){
                $servicios->where('id_cliente','=',$cliente);
            }
            
        }else{
             $filtros['cliente']="";
        }
        if(isset($filtros['conductor'])){
            $conductor=(int) $filtros['conductor'];
            if($conductor!="" || $conductor>0){
                $servicios->where('id_conductor_servicio','=',$conductor);
            }
            
        }else{
             $filtros['conductor']="";
        }

        if(isset($filtros['pasajero'])){
            
            $pasajero=$filtros['pasajero'];

            if($pasajero!="" ){

               $servicios ->leftJoin('pasajeros AS p', function($join){
                    $join->on('ordenes_servicio.id_pasajero', '=', 'p.id');
                   
            });
                 $servicios->where('p.nombres', 'LIKE', '%'.$pasajero.'%')
                            ->orwhere('p.apellidos', 'LIKE', '%'.$pasajero.'%'); 
            }
            
        }else{
             $filtros['pasajero']="";
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
        return view('servicios.index')->with(['servicios'=>$servicios,'filtros'=>$filtros]);
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
        $empleados=Empleado::where('area_empresa','4')->get();


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
            $servicio->user_id=Auth::user()->id;
            $servicio->save();
            \Session::flash('flash_message','Servicio agregado exitosamente!.');

             return redirect()->route('servicios');

         }else{
            $servicio=Servicio::find($request->get('id'));
            $servicio->update($request->all());
            $servicio->user_id=Auth::user()->id;
            
            //Tipo Anticipo
            if($request->get('tipo_anticipo')==1){
               $anticipo=Anticipos::where('conductor_id',$servicio->id_conductor_pago)->where('estado',0)->get()->first();

               if($anticipo){
                    $existe_abono=AnticiposAbonos::where('orden_servicio_id',$servicio->id)->get()->first();
                    if($existe_abono){
                        $abono=$existe_abono;
                    }else{
                        $abono=new AnticiposAbonos();
                        $abono->anticipo_id=$anticipo->id;
                        $abono->orden_servicio_id=$servicio->id;  
                    }
                    $abono->valor=$servicio->valor_conductor;
                    $abono->save();
               }
            }

            //Actualizamos valores de anticipos en la orden de servicio
            $total_anticipos=false;
            $total_abonos=false;
            $results_anticipos = DB::select( DB::raw("SELECT sum(valor) as 'total_anticipos' FROM anticipos WHERE estado=0 and conductor_id = :conductor_id"), array(
               'conductor_id' => $servicio->id_conductor_pago,
             ));

            $results_abonos = DB::select( DB::raw("SELECT sum(b.valor) as 'total_abonos' FROM anticipos_abonos b inner join anticipos a on b.anticipo_id=a.id WHERE a.estado=0 and a.conductor_id = :conductor_id"), array(
               'conductor_id' => $servicio->id_conductor_pago,
             ));
            if($results_anticipos){
                $total_anticipos=$results_anticipos[0]->total_anticipos;
            }
            if($results_abonos){
                $total_abonos=$results_abonos[0]->total_abonos;
            }
            if($total_anticipos && $total_abonos){

                $servicio->total_anticipos=$total_anticipos;
                $servicio->total_abonos=$total_abonos;
                
                if($total_anticipos>$total_abonos){
                    $saldo=$total_anticipos-$total_abonos;
                }else{
                    $saldo=$total_abonos-$total_anticipos;
                }
                
                if($saldo<0){
                    $saldo=0;
                }
                $descuento=0;
                $resta_descuento=$total_anticipos-$total_abonos;

                if($resta_descuento<0){
                    $descuento=$servicio->valor_conductor+$resta_descuento;
                }else{
                     $descuento=$servicio->valor_conductor;
                }
                if($descuento>=0){
                    $saldo=0;
                }
                if($resta_descuento<0){
                    $saldo=$total_abonos-$total_anticipos;
                }
                $servicio->descuento=$descuento;
                $servicio->saldo=$saldo;
                $servicio->save();


            }else{
                $servicio->saldo=$servicio->valor_conductor;
                $servicio->save();
            }
           
            \Session::flash('flash_message','Servicio actualizado exitosamente!.');

             return redirect()->route('servicios');

         }


    }
   
    public function update()
    { 
       
    }

 
    public function delete($id){
        
        $servicio=Servicio::find($id);

        $servicio->delete();

        \Session::flash('flash_message','Servicio eliminado exitosamente!.');

        return redirect()->back();


    }


    public function descargar(){
        $servicios=Servicio::whereIn('estado',array(1,2,3))->orderBy('fecha_servicio','Asc')->get();
        $tipo_servicios=TipoServicios::all();
        $fecha=date('Y-m-d');
        $filename = 'consolidado-semanal-'.$fecha.'.xls';
        //header('Content-type: application/excel');
        //header('Content-Disposition: attachment; filename='.$filename);
        $tabla=view('servicios.descargar')->with(['servicios'=>$servicios,'tipo_servicios'=>$tipo_servicios])->render();
        echo $tabla;
        exit();
    }

    private function getRepository(){
        return Servicio::paginate(Config::get('global_settings.paginate'));
    }
}
