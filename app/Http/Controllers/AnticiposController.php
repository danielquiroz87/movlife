<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Anticipos;
use App\Models\Propietarios;
use App\Models\Servicio;
use App\Models\Conductor;
use App\Models\Empleado;
use App\Models\Documentos;

use Config;

use App\Models\AnticiposAbonos;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use stdClass;

class AnticiposController extends Controller
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

    public function fromServicio(Request $request,$id){

        $servicio=Servicio::find($id);
        $valor=$request->get('valor');
        $tipo=$request->get('tipo_anticipo');
        $anticipo=new Anticipos();
        $anticipo->conductor_id=$servicio->id_conductor_servicio;
        $anticipo->cliente_id=$servicio->id_cliente;
        $anticipo->coordinador_id=$servicio->educador_coordinador;
        $anticipo->valor=$valor;
        $anticipo->valor_cliente=$servicio->valor_cliente;
        $anticipo->tipo=$tipo;
        if($valor==$servicio->valor_conductor){
            $anticipo->estado=1;
        }
        $anticipo->servicio_id=$servicio->id;
        $anticipo->save();
        
        \Session::flash('flash_message','Anticipo agregado exitosamente!.');
        return redirect()->back();
    }

    public function descargar(Request $request, $id){
        $anticipo=Anticipos::find($id);
        $conductor=Conductor::find($anticipo->conductor_id);
        $servicio=$anticipo->servicio;
        $cuenta_bancaria=new \stdClass();
        $cuentaBancaria=Documentos::where('id_tipo_documento','=',20)->where('id_registro','=',$conductor->id)->get()->first();
        if(!$cuentaBancaria){
            $cuenta_bancaria->tipo='NA';
            $cuenta_bancaria->numero="NA";
            $cuenta_bancaria->banco="NA";
        }else{
            $cuenta_bancaria->tipo=strtoupper($cuentaBancaria->extra1);
            $cuenta_bancaria->numero=$cuentaBancaria->numero_documento;
            $cuenta_bancaria->banco=$cuentaBancaria->nombre_entidad;
        }

        $coordinador=Empleado::find($anticipo->coordinador_id);
        return view('anticipos.descargar')->with(['anticipo'=>$anticipo,
                                                'servicio'=>$servicio,
                                                'coordinador'=>$coordinador,
                                                'cuenta_bancaria'=>$cuenta_bancaria
                                                ]
                                                );

    }
    public function index(Request $request)
    {   
        $anticipos=Anticipos::where('anticipos.id','>',0);
        $filtros=$request->get('filtros');

        if(isset($filtros['servicio_id'])){
            $servicio_id=$filtros['servicio_id'];
            if($servicio_id!=""){
               $anticipos->where('anticipos.servicio_id','=',$servicio_id); 
            }
        }else{
            $filtros['servicio_id']="";
        }

        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $anticipos->where('anticipos.created_at','>=',$fecha_inicial); 
            }
        }else{
            $filtros['fecha_inicial']=date('Y-m-01');
        }
        
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $anticipos->where('anticipos.created_at','<=',$fecha_final.' 23:59:59'); 
            }
            
        }else{
            $filtros['fecha_final']=date('Y-m-d');
        }

        if(isset($filtros['conductor'])){
            $conductor=$filtros['conductor'];
            if($conductor!=""){
               $anticipos->where('conductor_id',$conductor); 
            }
            
        }else{
            $filtros['conductor']="";
        }

        $request->session()->put('filtros.anticipos', $filtros);
        $anticipos=$anticipos->paginate(Config::get('global_settings.paginate')); 
        return view('anticipos.index')->with(['anticipos'=>$anticipos,'filtros'=>$filtros]);
    }

    public function descargarExcel(Request $request)
    {   
        $anticipos=Anticipos::where('anticipos.id','>',0);
        $filtros=$request->session()->get('filtros.anticipos');


        if(isset($filtros['servicio_id'])){
            $servicio_id=$filtros['servicio_id'];
            if($servicio_id!=""){
               $anticipos->where('anticipos.servicio_id','=',$servicio_id); 
            }
        }
        if(isset($filtros['fecha_inicial'])){
            $fecha_inicial=$filtros['fecha_inicial'];
            if($fecha_inicial!=""){
               $anticipos->where('anticipos.created_at','>=',$fecha_inicial); 
            }
        }
        
        if(isset($filtros['fecha_final'])){
            $fecha_final=$filtros['fecha_final'];
            if($fecha_final!=""){
               $anticipos->where('anticipos.created_at','<=',$fecha_final.' 23:59:59'); 
            }
            
        }
        if(isset($filtros['conductor'])){
            $conductor=$filtros['conductor'];
            if($conductor!=""){
               $anticipos->where('conductor_id',$conductor); 
            }
        }
        $fecha=date('Y-m-d');
        $filename = 'anticipos-del-'.$fecha_inicial.'-al-'.$fecha_final.'.xls';
        $tabla= view('anticipos.excel')->with(['anticipos'=>$anticipos->get()]);
        
        if(isset($_GET['revisar'])){
            var_dump($anticipos);
        }else{
            header('Content-type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename='.$filename);
        }
        echo $tabla;
        exit();
        
    }
    
    public function new()
    { 
       
        return view('anticipos.new');
    }
    
     public function edit($id)
    {   
        $anticipo=Anticipos::find($id);
        return view('anticipos.edit')->with(['anticipo'=>$anticipo]);

    }
    public function save(Request $request)
    { 
      
        $is_new=false;
        $user=false;
        $servicio=false;

        if($request->input('is_new') && $request->input('id')==0){
            $is_new=true;
            $anticipo=new Anticipos();
        }else{
            $id=(int) $request->input('id');
            if($id>0){
                $anticipo=Anticipos::find($id);
            }
        }
        if($is_new){
            $v = Validator::make($request->all(), [
                'conductor_id' => 'required',
                'cliente_id'=>'required',
                'coordinador_id'=>'required',
                'valor' => 'required',
            ]);   

          
        }else{

           $v = Validator::make($request->all(), [
                'conductor_id' => 'required',
                'cliente_id'=>'required',
                'coordinador_id'=>'required',
                'valor' => 'required',
            ]);   
          

        }
        

        if ($v->fails())
        {
            return redirect()->back()->withErrors($v->errors());
        }

        if($request->servicio_id!="" && $request->servicio_id>0){
            $servicio=Servicio::find($request->servicio_id);
            if(!$servicio){
                \Session::flash('flash_bad_message','Error, el servicio para el anticipo no existe!.');
                return redirect()->route('anticipos');
            }
        }
           
         if($is_new){

            $anticipo->create($request->all());
            \Session::flash('flash_message','Anticipo agregado exitosamente!.');

             return redirect()->route('anticipos');

         }else{

            $anticipo->update($request->all());

            \Session::flash('flash_message','Anticipo actualizado exitosamente!.');

             return redirect()->route('anticipos');

         }


    }
   
    public function update()
    { 
       
    }
     public function delete(Request $request,$id)
    { 
        $anticipo=Anticipos::find($id);
         $anticipo->delete();
         \Session::flash('flash_message','Anticipo eliminado exitosamente!.');
         return redirect()->route('anticipos');
    }
    private function getRepository(){
        return Anticipos::paginate(25);
    }
}
