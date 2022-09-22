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
        
        $servicios=$servicios->paginate(25);
        return view('facturas.index')->with(['servicios'=>$servicios]);
    }
    

    public function descargar(){
        $servicios=$this->getRepository();
        return view('servicios.descargar')->with(['servicios'=>$servicios]);
    }

    private function getRepository(){
        return Servicio::paginate(25);
    }
}
