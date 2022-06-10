<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UtilsController extends Controller
{
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function municipios(Request $request)
    {
        
        $id_depto=$request->get('id');
        $municipios=$this->getMunicipios($id_depto);
        $option_municipios="";
        foreach ($municipios as $municipio) { 
            $nombres=$municipio->nombre;
            $option_municipios.='<option value="'.$municipio->id.'">'.$nombres.'</option>';
        }
        
        return new Response($option_municipios);
        
    }

    public  function getMunicipios($departamentoId){
        
        return DB::table('municipios')
         -> where('id_departamento','=',$departamentoId)
         -> orderBy('nombre', 'asc')
         -> get();
    }


}
