Hola {{Helper::limpiarCadena($preservicio->pasajero_nombres)}} {{Helper::limpiarCadena($preservicio->pasajero_apellidos)}},
se ha registrado un nuevo PreServicio:
Id: {{$preservicio->id}},
Tipo Servicio: {{$preservicio->tipoServicio->nombre}},
Conductor: Pendiente,
Origen: {{Helper::limpiarCadena($preservicio->origen)}},
Destino: {{Helper::limpiarCadena($preservicio->destino)}},
Fecha servicio: {{date('d/m/y',strtotime($preservicio->fecha_servicio))}},
Hora de recogida: {{date('H:i',strtotime($preservicio->hora_recogida))}},
Celular Contacto: {{$preservicio->pasajero_celular}},
@if($preservicio->observaciones!="")
Observaciones: {{Helper::limpiarCadena($preservicio->observaciones)}}
@else
Observaciones: NA
@endif
Ahora puedes pedir tu vehículo desde el siguiente link:
https://app.movlife.co/web/solicitar/servicio