<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;

class Clientes extends Component
{
    
    public $clientes;	


    public function render()
    {
        
        //Cliente::paginate(Config::get('global_settings.paginate'));
        $clientes=Cliente::where('id','>=',1)->orderBy('id','Desc')->get();
        $k=0;
        foreach($clientes as $cliente){
            $this->clientes[]=$cliente;
            $k++;
            if($k>=25){
                break;
            }
        }
        return view('livewire.clientes');
    }
}
