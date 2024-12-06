<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vehiculo;
use App\Models\VehiculoAlistamientoDiarioDetalle;
use App\Models\VehiculoMantenimiento;
use App\Models\VehiculoMantenimientoDetalle;
use App\Models\VehiculoMantenimientoItems;


use Config;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;


class VehiculosMantenimientosController extends Controller
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
        //$mantenimientos=$this->getRepository();
        $mantenimientos = DB::table('vehiculos_mantenimientos_detalle')
        ->select('vehiculos_mantenimientos.*')
        ->addSelect('vehiculos_mantenimientos_detalle.id as detalle_id')
        ->addSelect('vehiculos_mantenimientos_detalle.km_ultima_revision')
        ->addSelect('vehiculos_mantenimientos_detalle.proveedor')
        ->addSelect('vehiculos_mantenimientos_detalle.observaciones')
        ->addSelect('vehiculos_mantenimientos_detalle.valor')
        ->addSelect('vehiculos_mantenimientos_detalle.km_restantes')
        ->addSelect('vehiculos_mantenimientos_detalle.intervalo_km')
        ->addSelect('vehiculos_mantenimientos_items.nombre')
        ->join('vehiculos_mantenimientos','vehiculos_mantenimientos_detalle.mantenimiento_id', '=', 'vehiculos_mantenimientos.id')
        ->join('vehiculos_mantenimientos_items','vehiculos_mantenimientos_detalle.item_id', '=', 'vehiculos_mantenimientos_items.id');

        $q="";
        if($request->has('q')){
            if($request->get('q')!=""){
                $search=$request->get('q');
                $q=$search;
                
                $mantenimientos=$mantenimientos->where('vehiculos_mantenimientos.placa','LIKE', '%'.$search.'%');
            }
        }
        $mantenimientos=$mantenimientos->paginate(Config::get('global_settings.paginate'));                           

        return view('vehiculos_mantenimientos.index')->with(['mantenimientos'=>$mantenimientos,'q'=>$q]);
    }
    public function new()
    { 
        $items=VehiculoMantenimientoItems::all();

        return view('vehiculos_mantenimientos.new')->with(['items'=>$items]);
    }
    public function edit($id)
    {   
        $detalleMantenimiento=VehiculoMantenimientoDetalle::find($id);
        $mantenimiento=VehiculoMantenimiento::find($detalleMantenimiento->mantenimiento_id);
        $items=VehiculoMantenimientoItems::all();
        
        return view('vehiculos_mantenimientos.edit')->with(['mt'=>$mantenimiento,'items'=>$items,'detItems'=>$detalleMantenimiento]);
    }
    public function save(Request $request)
    { 
      
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $mantenimiento=new VehiculoMantenimiento();
        }else{
            $is_new=false;
            $id=(int) $request->input('id');
            if($id>0){
                $mantenimiento=VehiculoMantenimiento::find($id);
            }
        }
        $v = Validator::make($request->all(), [
                'fecha'=>'required',
                'placa' => 'required|max:255',
                'kilometros'=>'required',
                //'direccion'=>'required'

            ]);   
        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        $mantenimiento->fecha=$request->get('fecha');
        $mantenimiento->placa=$request->get('placa');
        $mantenimiento->kilometros=$request->get('kilometros');
        $mantenimiento->save();

        $dt=new VehiculoMantenimientoDetalle();
        $dt->mantenimiento_id=$mantenimiento->id;
        $dt->item_id=$request->get('item');
        $dt->tipo_mantenimiento=$request->get('tipo_mantenimiento');
        $dt->proveedor=$request->get('proveedor');
        $dt->valor=$request->get('valor');
        $dt->observaciones=$request->get('observaciones');
        $dt->save();

        if($is_new){
            \Session::flash('flash_message','Mantenimiento creada exitosamente!.');

        }else{
            \Session::flash('flash_message','Mantenimiento actualizada exitosamente!.');
        }

         return redirect()->route('vehiculos.mantenimientos');
    }

    public function saveItems(Request $request){

        $mantenimiento=VehiculoMantenimiento::find($request->mantenimiento_id);
        $id=$request->get('item');    
        $existeD=VehiculoMantenimientoDetalle::find($request->get('detalle_id'));
        $item=VehiculoMantenimientoItems::find($id);
       
        if($existeD){
            $dt=$existeD;
        }else{
            $dt=new VehiculoMantenimientoDetalle();
        }   

        $dt->item_id=$item->id;
        $dt->tipo_mantenimiento=$request->get('tipo_mantenimiento');
        $dt->proveedor=$request->get('proveedor');
        $dt->valor=$request->get('valor');
        $dt->observaciones=$request->get('observaciones');
        
        if($item->tipo==1){
            $dt->intervalo_km=$item->intervalo_km;
            $dt->km_ultima_revision=0;
            $dt->km_restantes=($item->intervalo_km+0)-$mantenimiento->kilometros;
        }else{
            $dt->intervalo_km=$item->intervalo_years;
            $dt->km_ultima_revision=0;
            $dt->km_restantes=($item->intervalo_km+0)-$mantenimiento->kilometros;
          
        }
        \Session::flash('flash_message','Mantenimiento actualizado exitosamente!.');

        $dt->save();
        return redirect()->route('vehiculos.mantenimientos');

    }

    public function update()
    { 
       
    }
    public function delete($id){
        $sede=VehiculoMantenimiento::find($id);
        $sede->delete();

        \Session::flash('flash_message','Mantenimiento eliminado exitosamente!.');

         return redirect()->route('vehiculos.mantenimientos');


    }
   
    private function getItemDetalle($mantenimientoId,$itemId){
       $dt=VehiculoMantenimientoDetalle::where('mantenimiento_id',$mantenimientoId)
                                                   ->where('item_id',$itemId)
                                                    ->orderBy('id','Desc')
                                                    ->get()->first();
        if($dt){
            return $dt;
        }else{
         
           return false;
        }
    }
    private function getRepository(){
        return VehiculoMantenimiento::paginate(Config::get('global_settings.paginate'));
    }
}
