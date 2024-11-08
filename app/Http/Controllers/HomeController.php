<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Empleado;
use App\Models\Conductor;
use App\Models\Cliente;


class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        $user_id=Auth::user()->id;
        //Es empleado
        $is_employe=Empleado::where('user_id',$user_id)->get()->first();

        if(Auth::user()->superadmin==1){
             session(['is_employe' =>false]);
             session(['is_superadmin' =>true]);
             session(['employe' =>false]);
             session(['is_employe' =>false]);
             session(['is_driver' =>false]);
             session(['driver' =>false]);

        }
        if(is_object($is_employe)){
            session(['is_employe' =>true]);
            session(['employe' =>$is_employe]);
            session(['is_driver' =>false]);
            session(['driver' =>false]);
        }
        else{
            $is_driver=Conductor::where('user_id',$user_id)->get()->first();
            if(is_object($is_driver)){
                session(['is_employe' =>false]);
                session(['is_driver' =>true]);
                session(['driver' =>$is_driver]);
                return redirect()->route('vehiculos');
            }
            $is_client=Cliente::where('user_id',$user_id)->get()->first();
            if($is_client){
                session(['is_employe' =>false]);
                session(['is_superadmin' =>false]);
                session(['employe' =>false]);
                session(['is_driver' =>false]);
                session(['driver' =>false]);  
                session(['is_client' =>true]);
                session(['client' =>$is_client]);
                return redirect()->route('servicios');
            }
        }


        return view('home');
    }

    

    
}
