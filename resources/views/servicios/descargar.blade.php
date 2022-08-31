<?php
$tipo_servicios=array(1=>'Visitas Domiciliarias',2=>'Traslado Pacientes');
$tipo_viaje=array(1=>'Ida',2=>'Ida y Regreso',3=>'Regreso');

?>
<table  border="1" style="border-collapse: collapse" >
<thead>
<tr>
<th colspan="17">LOGO</th>
<th colspan="16">CUADRO CIERRE SEMANAL</th>
<th colspan="16">FR-05-021-00<br>
				Página 1 de 1<br> 
				<?php echo date('d/m/Y')	;?>				
</th>
</tr>
<tr>
<th>CONSECUTIVO</th>
<th>FECHA DE SOLICITUD DEL SERVICIO</th>
<th>FECHA DE PRESTACION DEL SERVICIO</th>
<th>TIPO DE SERVICIO</th>
<th>COORDINADOR	</th>
<th>SEMANA</th>
<th>PERSONA A TRANSPORTAR</th>
<th>DOCUMENTO PERSONA A TRANSPORTAR	</th>
<th>TELEFONO PACIENTE	</th>
<th>CLIENTE	</th>
<th>UR / SEDE</th>
<th>CIUDAD</th>
<th>CODIGO DEL PACIENTE</th>
<th>DIRECCIÓN AUTORIZADA RECOGIDA 	</th>
<th>BARRIO</th>	
<th>ORIGEN</th>	
<th>DESTINO </th>	
<th>KILOMETRAJE	</th>
<th>TIEMPO 	</th>
<th>HORA DE RECOGIDA</th>
<th>SERVICIO IDA / REGRESO / CUMPLIMIENTO	</th>
<th>TURNO	 </th>
<th>EDUCADORA / COORDINADORA</th>	
<th>HORA DE LA CITA-INFUSION-EXAMEN	</th>
<th>HORA DE TERMINACION DE LA CITA/INFUSION/ EXAMEN	 </th>
<th>TERAPIA	</th>
<th>PROGRAMA 	 </th>
<th>OBSERVACIONES	</th>
<th>CEDULA CONDUCTOR PRINCIPAL 	</th>
<th>CONDUCTOR PRINCIPAL (A QUIEN SE LE PAGA)</th>
<th>CONDUCTOR QUE HACE EL SERVICIO 	</th>
<th>TELEFONO CONDUCTOR 	</th>
<th>PERSONA AUTORIZADA PARA PAGO	</th>
<th>CEDULA -  AUTORIZADA PARA PAGO	 </th>
<th>COSTO	 </th>
<th>DESCUENTO PRESTAMO	 </th>
<th>PRECIO ALIMENTACIÓN CONDUCTOR 	</th> 
<th>TOTAL CON DESCUENTO INCLUIDO </th>	 
<th>TARIFA CLIENTE	 </th>
<th>FACTURA	 </th>
<th>ANTICIPO	 </th>
<th>NUMERO PAGO	</th>
<th>FECHA DE PAGO	</th>
<th>BANCO	 </th>
<th>VALOR BANCO  Y/O ANTICIPO </th>	 
<th>SALDO 	 </th>
<th>NO ORDEN DE COMPRA 	 </th>
<th>DOC CONTABLE	</th>
<th>OBSERVACIONES </th>
<th>COORDINADOR	</th>
<th>OBSERVACIONES CONTABILIDAD</th>
</tr>
</thead>
<tbody>
	@foreach ($servicios as $servicio)
	<tr>
	<td>{{$servicio->id}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td><?php echo $tipo_servicios[$servicio->tipo_servicio];?></td>
	<td>NA</td>
	<td>{{$servicio->semana}}</td>
	<td>{{$servicio->pasajero->nombres}} {{$servicio->pasajero->apellidos}}</td>
	<td>{{$servicio->pasajero->documento}}
	<td>{{$servicio->pasajero->telefono}}</td>
	<td>{{$servicio->cliente->documento}},{{$servicio->cliente->nombres}} {{$servicio->cliente->apellidos}}</td>
	<td>{{$servicio->uri_sede}}</td>
	<td>Ciudad Sede</td>
	<td>{{$servicio->pasajero->id}}</td>
	<td>{{$servicio->origen}}</td>
	<td>{{$servicio->barrio}}</td>
	<td>{{$servicio->origen}}</td>
	<td>{{$servicio->destino}}</td>
	<td>{{$servicio->kilometraje}}</td>
	<td>{{$servicio->tiempo}}</td>
	<td>{{$servicio->hora_recogida}}</td>
	<td>{{$tipo_viaje[$servicio->tipo_viaje]}}</td>
	<td>{{$servicio->turno}}</td>
	<td>{{$servicio->educador_coordinador}}</td>
	<td>{{$servicio->hora_cita}}</td>
	<td>{{$servicio->hora_terminacion_cita}}</td>
	<td>{{$servicio->terapia}}</td>
	<td>{{$servicio->programa}}</td>
	<td>{{$servicio->observaciones}}</td>
	<td>{{$servicio->conductor->documento}}</td>
	<td>{{$servicio->conductor->nombres}} {{$servicio->conductor->apellidos}}</td>
	<td>{{$servicio->conductorServicio->nombres}}</td>
	<td>{{$servicio->conductorServicio->celular}}</td>
	<td>{{$servicio->conductor->nombres}} {{$servicio->conductor->apellidos}}</td>
	<td>{{$servicio->conductor->documento}}</td>
	<td>{{$servicio->valor_conductor}}</td>
	<td>0</td>
	<td>0</td>
	<td>{{$servicio->valor_conductor}}</td>
	<td>{{$servicio->valor_cliente}}</td>
	<td>{{$servicio->factura}}</td>
	<td>{{$servicio->anticipo}}</td>
	<td>{{$servicio->id}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td>{{$servicio->banco}}</td>
	<td>{{$servicio->valor_conductor}}</td>
	<td>0</td>
	<td>{{$servicio->id}}</td>
	<td>{{$servicio->id}}</td>
	<td></td>
	<td>{{$servicio->user}}</td>
	<td>{{$servicio->observaciones}}</td>
	<tr>
	@endforeach
</tbody>
</table>

