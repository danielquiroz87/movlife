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
    

}
