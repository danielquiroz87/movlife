<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Config;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
	public function index(Request $request)
    {   
        
        return view('perfil.edit');
    }

    public function save(Request $request)
    { 
    	

    	$user=User::find($request->get('id'));

    	if($request->get('password')!=""){
                $user->password=Hash::make($request->get('password'));
        }
        $user->save();
        
        \Session::flash('flash_message','Perfil actualizado exitosamente!.');

        return redirect()->route('perfil');
    }
}