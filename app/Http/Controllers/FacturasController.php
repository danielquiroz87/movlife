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

class FacturasController extends Controller
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
        $servicios=Servicio::whereIn('estado',array(3));
        
        $filtros=$request->get('filtros');

        if(isset($filtros['estado'])){
            $estado=(int) $filtros['estado'];
            if($estado!="" || $estado>0){
                $servicios->where('estado','=',$estado);
            }
        }
        else{
            $filtros['estado']=3;
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

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $servicios->where('fecha_servicio','>=',$fecha_inicial); 
            }
            
        }else{
            $filtros['fecha_inicial']=date('Y-m-01');
        }
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $servicios->where('fecha_servicio','<=',$fecha_final); 
            }
            
        }else{
            $filtros['fecha_final']=date('Y-m-d');
        }

        $servicios=$servicios->paginate(25);
        return view('facturas.index')->with(['servicios'=>$servicios,'filtros'=>$filtros]);
    }
    

    public function descargar(){
        $servicios=$this->getRepository();
        return view('servicios.descargar')->with(['servicios'=>$servicios]);
    }

    private function getRepository(){
        return Servicio::paginate(25);
    }
}
