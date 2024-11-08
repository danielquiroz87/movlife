<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Servicio;


class ContadoresServicios extends Component
{

    public $activos;
    public $cumplidos;
    public $cancelados;
    public $proceso;
    public $lastId;

    public function render()
    {
        $this->activos=0;
        $this->cumplidos=0;
        $this->cancelados=0;
        $this->proceso=0;
        $this->lastId=0;

        $this->activos=Servicio::where('estado',1)->count();
        $this->proceso=Servicio::where('estado',2)->count();
        $this->cumplidos=Servicio::where('estado',3)->count();
        $this->cancelados=Servicio::where('estado',4)->count();
        $ultimo=Servicio::select('id')->orderBy('id','Desc')->get()->first();
        $this->lastId=$ultimo->id;

        return view('livewire.contadores-servicios');
    }
}
