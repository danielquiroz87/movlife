<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Usuarios;

class UsuariosController extends Controller
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
    public function index()
    {   
        $usuarios=$this->getRepository();
        return view('usuarios.index')->with(['usuarios'=>$usuarios]);
    }
    public function new()
    { 
        return view('usuarios.new');
    }
    public function save()
    { 
       
    }
    public function edit()
    { 
       
    }
    public function update()
    { 
       
    }
    private function getRepository(){
        return Usuarios::paginate(25);
    }
}
