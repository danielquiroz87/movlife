<?php

namespace App\Exports;

use App\Models\Pasajero;
use Maatwebsite\Excel\Concerns\FromCollection;

class PasajerosExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pasajero::all();
    }
}
    