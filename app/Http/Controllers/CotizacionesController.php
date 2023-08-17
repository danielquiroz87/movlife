<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\User;
use App\Models\Direccion;
use App\Models\CotizacionDetalle;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;


class CotizacionesController extends Controller
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
        $cotizaciones=Cotizacion::paginate(25);
    

        return view('cotizaciones.index')->with(['cotizaciones'=>$cotizaciones]);
    }
    public function new()
    { 
        return view('cotizaciones.new');
    }
     public function edit($id)
    {   
        $cotizacion=Cotizacion::find($id);
        $direcciones=CotizacionDetalle::where('cotizacion_id','=',$cotizacion->id)->get();    
        return view('cotizaciones.edit')->with(['cotizacion'=>$cotizacion,'direcciones'=>$direcciones]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $cotizacion=new Cotizacion();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $cotizacion=Cotizacion::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), []);   

          
        }else{

            $v = Validator::make($request->all(), []);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

       
         if($is_new){

            $cotizacion->id_cliente=$request->id_cliente;
            $cotizacion->descripcion=$request->descripcion;
            $cotizacion->forma_pago=$request->forma_pago;

            $cotizacion->fecha_cotizacion=$request->fecha_cotizacion;
            $cotizacion->fecha_vencimiento=$request->fecha_vencimiento;
            $cotizacion->fecha_servicio=$request->fecha_servicio;
            $cotizacion->hora_recogida=$request->hora_recogida;
            $cotizacion->hora_salida=$request->hora_salida;
            $cotizacion->tipo_viaje=$request->tipo_viaje;
            $cotizacion->tiempo_adicional=$request->tiempo_adicional;
            $cotizacion->horas_tiempo_adicional=$request->horas_tiempo_adicional;
            $cotizacion->direccion_recogida=$request->origen;
            $cotizacion->direccion_destino=$request->destino;

            $cotizacion->valor=$request->valor_unitario;
            $cotizacion->cantidad=$request->cantidad;
            $cotizacion->total=$request->valor_unitario*$request->cantidad;
            $cotizacion->observaciones=$request->observaciones;
            $cotizacion->comentarios=$request->comentarios;
            $cotizacion->finalizada=0;
            $cotizacion->id_user=$request->id_user;
            $cotizacion->contacto_nombres=$request->contacto_nombres;
            $cotizacion->contacto_telefono=$request->contacto_telefono;
            $cotizacion->contacto_email=$request->contacto_email;


            $file = $request->file('foto');

            if(is_object($file)){
                $destinationPath = 'uploads';
                $file->move($destinationPath,$file->getClientOriginalName());
                $cotizacion->foto_vehiculo=$destinationPath.'/'.$file->getClientOriginalName();
            }

            $cotizacion->save();


             if($request->get('origen')!=""){

                $cd=new CotizacionDetalle();
                $cd->cotizacion_id=$cotizacion->id;
                $cd->origen=$request->get('origen');
                $cd->destino=$request->get('destino');
                $cd->destino2=$request->get('destino2');
                $cd->destino3=$request->get('destino3');
                $cd->destino4=$request->get('destino4');
                $cd->destino5=$request->get('destino5');
                $cd->valor=$request->get('valor_unitario',0);
                $cd->cantidad=$request->get('cantidad',1);
                $cd->total=($cd->cantidad*$cd->valor);
                $cd->save();
            }

            


            //$user->create($request->all());
            \Session::flash('flash_message','Cotización agregada exitosamente!.');

             return redirect()->route('cotizaciones.edit',['id'=>$cotizacion->id]);

         }else{

            $cotizacion->id_cliente=$request->id_cliente;
            $cotizacion->descripcion=$request->descripcion;
            $cotizacion->forma_pago=$request->forma_pago;
 
            $cotizacion->fecha_cotizacion=$request->fecha_cotizacion;
            $cotizacion->fecha_vencimiento=$request->fecha_vencimiento;
            $cotizacion->fecha_servicio=$request->fecha_servicio;
            $cotizacion->hora_recogida=$request->hora_recogida;
            $cotizacion->hora_salida=$request->hora_salida;
            $cotizacion->tipo_viaje=$request->tipo_viaje;
            $cotizacion->tiempo_adicional=$request->tiempo_adicional;
            $cotizacion->horas_tiempo_adicional=$request->horas_tiempo_adicional;
            
            $cotizacion->valor=$request->valor_unitario;
            $cotizacion->cantidad=$request->cantidad;
            $cotizacion->total=$request->valor_unitario*$request->cantidad;
            $cotizacion->observaciones=$request->observaciones;
            $cotizacion->comentarios=$request->comentarios;
            $cotizacion->finalizada=$request->finalizada;
            $cotizacion->contacto_nombres=$request->contacto_nombres;
            $cotizacion->contacto_telefono=$request->contacto_telefono;
            $cotizacion->contacto_email=$request->contacto_email;


            $file = $request->file('foto');

            if(is_object($file)){
                $destinationPath = 'uploads';
                if($cotizacion->foto_vehiculo!=""){
                    unlink($cotizacion->foto_vehiculo);
                }
                $file->move($destinationPath,$file->getClientOriginalName());
                $cotizacion->foto_vehiculo=$destinationPath.'/'.$file->getClientOriginalName();
            }
            

            $cotizacion->save();

            if($request->get('origen')!=""){

                $cd=new CotizacionDetalle();
                $cd->cotizacion_id=$cotizacion->id;
                $cd->origen=$request->get('origen');
                $cd->destino=$request->get('destino');
                $cd->destino2=$request->get('destino2');
                $cd->destino3=$request->get('destino3');
                $cd->destino4=$request->get('destino4');
                $cd->destino5=$request->get('destino5');
                $cd->valor=$request->get('valor_unitario',0);
                $cd->cantidad=$request->get('cantidad',1);
                $cd->total=($cd->cantidad*$cd->valor);
                var_dump($cd);
                die();
                $cd->save();
            }

            \Session::flash('flash_message','Cotización actualizada exitosamente!.');

            return redirect()->back();

         }


    }
    public function saveItem(Cotizacion $cotizacion,Request $request){

        if($request->get('origen')!=""){

            $cd=new CotizacionDetalle();
            $cotizacion=Cotizacion::find($request->get('id'));
            $cd->cotizacion_id=$cotizacion->id;
            $cd->origen=$request->get('origen');
            $cd->destino=$request->get('destino');
            $cd->destino2=$request->get('destino2');
            $cd->destino3=$request->get('destino3');
            $cd->destino4=$request->get('destino4');
            $cd->destino5=$request->get('destino5');
            $cd->valor=$request->get('valor_unitario',0);
            $cd->cantidad=$request->get('cantidad',1);
            $cd->total=($cd->cantidad*$cd->valor);
            $cd->save();

        \Session::flash('flash_message','Cotización actualizada exitosamente!.');

        }
       
        return response()->json([
            'code' => '200',
            'message' => 'Cotización actualizada exitosamente!.',
        ]);
    }

    public function update()
    { 
       
    }
    public function delete($id){
        $cotizacion=Cotizacion::find($id);
        $cotizacion->delete();

        \Session::flash('flash_message','Cotización eliminada exitosamente!.');

        return redirect()->back();


    }
    public function deleteDetalle($id){

        $cotizacion=CotizacionDetalle::find($id);
        $cotizacion->delete();

        \Session::flash('flash_message','Item cotización eliminada exitosamente!.');

        return redirect()->back();


    }
    public function descargar($id){
        
        /*
        $cotizacion=Cotizacion::find($id);
        $detalle=$cotizacion->detalle();
        return view('cotizaciones.descargar')->with(['cotizacion'=>$cotizacion]);
        */
        
        $cotizacion=Cotizacion::find($id);
        $detalle=$cotizacion->detalle();

        $filename = 'cotizacion-'.$id.'.xls';
        header('Content-type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename='.$filename);
        $tabla=view('cotizaciones.descargar')->with(['cotizacion'=>$cotizacion])->render();
        echo $tabla;
        exit();
        
    }

    private function getRepository(){
        return Cotizacion::paginate(25);
    }
}
