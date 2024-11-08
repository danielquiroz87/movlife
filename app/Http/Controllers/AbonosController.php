<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnticiposAbonos;
use App\Models\Anticipos;



use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AbonosController extends Controller
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
    public function index(Request $request,$id)
    {   
        
        $abonos=AnticiposAbonos::where('anticipo_id','=',$id);
        $abonos=$abonos->paginate(25);
        $anticipo=Anticipos::find($id);
        return view('anticipos.abonos')->with(['abonos'=>$abonos,'anticipo'=>$anticipo]);
    }

    public function new(Request $request,$id){
        $anticipo=Anticipos::find($id);
        return view('anticipos.abonos_new')->with(['anticipo'=>$anticipo]);
    }

    public function save(Request $request){
        $anticipo=Anticipos::find($request->get('anticipo_id'));
        $abono=new AnticiposAbonos();
        $abono->anticipo_id=$anticipo->id;
        if($request->has('servicio_id')){
            $abono->orden_servicio_id=$request->get('servicio_id');
        }
        $abono->valor=$request->get('valor');
        $abono->save();
        \Session::flash('flash_message','Abono agregado exitosamente!.');

        return redirect()->route('anticipos');


    }
    

}
