Hola {{Helper::limpiarCadena($servicio->pasajero->nombres)}} {{Helper::limpiarCadena($servicio->pasajero->apellidos)}},se ha confirmado tu vehículo, de placa:{{$servicio->vehiculo->placa}},
Id:{{$servicio->id}},Tipo Servicio:{{$servicio->tipoServicio->nombre}},
Conductor:{{Helper::limpiarCadena($servicio->conductorServicio->nombres)}} {{Helper::limpiarCadena($servicio->conductorServicio->apellidos)}},
Origen:{{Helper::limpiarCadena($servicio->origen)}},Destino:{{Helper::limpiarCadena($servicio->destino)}},
Fecha servicio:{{date('d/m/y',strtotime($servicio->fecha_servicio))}},Hora de recogida: {{date('H:i',strtotime($servicio->hora_recogida))}},
Celular Contacto: {{$servicio->pasajero->celular}},
@if($servicio->observaciones!="")
Observaciones: {{Helper::limpiarCadena($servicio->observaciones)}}
@else
Observaciones: NA
@endif