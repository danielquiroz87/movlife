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


class TarifarioController extends Controller
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
        $tarifario=Tarifario::paginate(25);

        return view('tarifario.index')->with(['cotizaciones'=>$cotizaciones]);
    }
    public function new()
    { 
        return view('cotizaciones.new');
    }
    public function edit($id)
    {   
        $tarifario=Tarifario::find($id);
        return view('tarifario.edit')->with(['tarifario'=>$tarifario);

    }
    public function save(Request $request)
    { 
      
        

    }


    public function update()
    { 
       
    }
    public function delete($id){
        $tarifario=Tarifario::find($id);
        $tarifario->delete();

        \Session::flash('flash_message','Registro eliminado exitosamente!.');

        return redirect()->back();


    }
   

    private function getRepository(){
        return Cotizacion::paginate(Config::get('global_settings.paginate'));
    }
}
