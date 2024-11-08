<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TarifasTipoServicio;


use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Storage;
use Config;


class TarifasTipoServicioController extends Controller
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
        $tarifas=TarifasTipoServicio::paginate(Config::get('global_settings.paginate'));
        $q="";
        if($request->has('q')){
           $q=$request->get('q');
           $tarifas=TarifasTipoServicio::where('destino','LIKE', '%'.$q.'%');
           $tarifas=$tarifas->paginate(Config::get('global_settings.paginate'));      
        }

        return view('tarifastiposervicio.index')->with(['tarifas'=>$tarifas,'q'=>$q]);
    }
    public function new()
    { 
        return view('tarifastiposervicio.new');
    }

    public function edit($id)
    {   
        $tarifario=TarifasTipoServicio::find($id);
        return view('tarifastiposervicio.edit')->with(['tarifario'=>$tarifario]);

    }

    public function match(Request $request)
    {   
        $existe_tarifa=TarifasTipoServicio::where('cliente_id',$request->id_cliente)
        ->where('tipo_servicio',$request->tipo_servicio)
        ->where('uri_sede',$request->uri_sede)->get()->first();
        $valor_conductor="";
        $valor_cliente="";

        if($existe_tarifa){

        }else{
        $existe_tarifa=TarifasTipoServicio::where('cliente_id',$request->id_cliente)
                ->where('tipo_servicio',$request->tipo_servicio)
                ->where('destino',$request->destino)->get()->first();
        }
        if($existe_tarifa){
            $valor_conductor=$existe_tarifa->valor_conductor;
            $valor_cliente=$existe_tarifa->valor_cliente;
        }else{

        }
        return response()->json([
            'data' => ['valor_conductor'=>$valor_conductor,'valor_cliente'=>$valor_cliente]
        ]);

    }

    public function save(Request $request)
    { 
        $is_new=false;
        $user=false;
        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $tarifas=new TarifasTipoServicio();
            
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $tarifas=TarifasTipoServicio::find($id);
            }
        }
       

        if($is_new){
            $tarifas=TarifasTipoServicio::create($request->all());
            $tarifas->save();
            \Session::flash('flash_message','Tarifa agregada exitosamente!.');
            return redirect()->route('tarifastiposervicio');

        }else{
            $tarifas->update($request->all());
            $tarifas->save();
            \Session::flash('flash_message','Tarifas actualizada exitosamente!.');
            return redirect()->route('tarifastiposervicio');
        }

    }


    public function update()
    { 
       
    }
    public function delete($id){
        $tarifario=TarifasTipoServicio::find($id);
        $tarifario->delete();

        \Session::flash('flash_message','Registro eliminado exitosamente!.');

        return redirect()->back();


    }
   

    private function getRepository(){
        return TarifasTipoServicio::paginate(Config::get('global_settings.paginate'));
    }
}
