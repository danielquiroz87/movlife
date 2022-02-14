<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion;
use App\Models\User;
use App\Models\Direccion;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

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
    public function index()
    {   
        $cotizaciones=$this->getRepository();
        return view('cotizaciones.index')->with(['cotizaciones'=>$cotizaciones]);
    }
    public function new()
    { 
        return view('cotizaciones.new');
    }
     public function edit($id)
    {   
        $cotizacion=Cotizacion::find($id);
       
        return view('cotizaciones.edit')->with(['cotizacion'=>$cotizacion]);

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
            $cotizacion->fecha_cotizacion=$request->fecha_cotizacion;
            $cotizacion->fecha_vencimiento=$request->fecha_vencimiento;
            $cotizacion->direccion_recogida=$request->direccion_recogida;
            $cotizacion->direccion_destino=$request->direccion_destino;
            $cotizacion->tipo_viaje=2;
            $cotizacion->valor=$request->valor_cliente;
            $cotizacion->valor=$request->valor_unitario;
            $cotizacion->cantidad=$request->cantidad;
            $cotizacion->total=$request->valor_unitario*$request->cantidad;
            $cotizacion->observaciones=$request->observaciones;
            $cotizacion->comentarios=$request->comentarios;
            $cotizacion->save();

            //$user->create($request->all());
            \Session::flash('flash_message','Cotización agregada exitosamente!.');

             return redirect()->route('cotizaciones.edit',['id'=>$cotizacion->id]);

         }else{

            $cotizacion->id_cliente=$request->id_cliente;
            $cotizacion->descripcion=$request->descripcion;
            $cotizacion->fecha_cotizacion=$request->fecha_cotizacion;
            $cotizacion->fecha_vencimiento=$request->fecha_vencimiento;
            $cotizacion->direccion_recogida=$request->direccion_recogida;
            $cotizacion->direccion_destino=$request->direccion_destino;
            $cotizacion->tipo_viaje=2;
            $cotizacion->valor=$request->valor_cliente;
            $cotizacion->valor=$request->valor_unitario;
            $cotizacion->cantidad=$request->cantidad;
            $cotizacion->total=$request->valor_unitario*$request->cantidad;
            $cotizacion->observaciones=$request->observaciones;
            $cotizacion->comentarios=$request->comentarios;
            $cotizacion->save();

             $cotizacion->save();

            \Session::flash('flash_message','Cotización actualizada exitosamente!.');

            return redirect()->back();

         }


    }
   
    public function update()
    { 
       
    }
    public function delete(Cotizacion $cotizacion){
        $cotizacion->delete();

        \Session::flash('flash_message','Cotización eliminada exitosamente!.');

        return redirect()->back();


    }
    private function getRepository(){
        return Cotizacion::paginate(25);
    }
}
