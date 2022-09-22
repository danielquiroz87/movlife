<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cliente;

class Clientes extends Component
{
    
    public $clientes;	


    public function render()
    {
        
        $this->clientes=Cliente::all();
 	

        return view('livewire.clientes');
    }
}
