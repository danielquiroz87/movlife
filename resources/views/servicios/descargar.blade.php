<?php
$tipo_viaje=array(1=>'Ida',2=>'Ida y Regreso',3=>'Regreso',4=>'Multiviaje');

?>
<table  border="1" style="border-collapse: collapse" >
<thead>
<tr>
<th colspan="17"><img src="{{asset('images/movlife.png')}}"></th>
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
<th>PLACA</th>
<th>CEDULA PLACA</th>
<th>ESPECIAL</th>
<th>INTERMUNICIPAL</th>
<th>TAXI</th>
<th>PARTICULAR</th>
<th>CEDULA</th>
<th>LICENCIA DE CONDUCIR</th>
<th>PLANILLA DE SS</th>
<th>RUT</th>
<th>SIMIT</th>
<th>RUNT</th>
<th>CERT-ANTECEDENTES</th>
<th>CURSOS ADICIONALES</th>
<th>FOTO VEHICULO</th>
<th>LICENCIA DE TRANS</th>
<th>SOAT</th>
<th>REVISIÓN TEC</th>
<th>TARJETA OPERACIÓN</th>
<th>POLIZA</th>
<th>REVISION PREVENTIVA</th>
<th>EMPRESA AFILIADORA</th>
<th>FECHA VENCIMIENTO LICENCIA DE CONDUCCION</th>
<th>DIAS PARA VENCIMIENTO </th>
<th>FECHA DOCUMENTOS SEGURO OBLIGATORIO</th>
<th>DIAS PARA VENCIMIENTO</th>
<th>FECHA DOCUMENTOS POLIZA CONTRA</th>
<th>DIAS PARA VENCIMIENTO</th>
<th>FECHA DOCUMENTOS POLIZA EXTRA</th>
<th>DIAS PARA VENCIMIENTO</th>
<th>FECHA DOCUMENTOS TARJETA OPERACION</th>
<th>DIAS PARA VENCIMIENTO</th>
<th>FECHA DOCUMENTOS TECNOMECANICA</th>
<th>DIAS PARA VENCIMIENTO</th>
<th>FECHA DOCUMENTOS TECNOMECANICA</th>
<th>DIAS PARA VENCIMIENTO</th>


</tr>
</thead>
<tbody>
	@foreach ($servicios as $servicio)
	<tr>
	<?php 
	$documentos_conductor=Helper::getDocumentosConductor($servicio->id_conductor_servicio);
	$documentos_vehiculo=Helper::getDocumentosVehiculo($servicio->placa);
	?>

	<td>{{$servicio->id}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td><?php echo $tipo_servicios[$servicio->tipo_servicio]->nombre;?></td>
	<td>
		@if($servicio->educador_coordinador>0)
			{{$servicio->coordinador->nombres}} {{$servicio->coordinador->apellidos}}
		@else
			NA
		@endif
	</td>
	<td>{{$servicio->semana}}</td>
	<td>{{$servicio->pasajero->nombres}} {{$servicio->pasajero->apellidos}}</td>
	<td>{{$servicio->pasajero->documento}}
	<td>{{$servicio->pasajero->telefono}}</td>
	<td>{{$servicio->cliente->documento}},{{$servicio->cliente->nombres}} {{$servicio->cliente->apellidos}}</td>
	<td>
		@if($servicio->uri_sede>0)
			{{$servicio->sede->nombre}}
		@else
		NA
		@endif
	</td>
	<td>		
		@if($servicio->uri_sede>0)
			{{$servicio->sede->ciudad->nombre}}

		@else
			NA
		@endif
	</td>
	<td>{{$servicio->pasajero->id}}</td>
	<td>{{$servicio->origen}}</td>
	<td>{{$servicio->barrio}}</td>
	<td>{{$servicio->origen}}</td>
	<td>{{$servicio->destino}}</td>
	<td>{{$servicio->kilometros}}</td>
	<td>{{$servicio->tiempo}}</td>
	<td>{{$servicio->hora_recogida}}</td>
	<td>{{$tipo_viaje[$servicio->tipo_viaje]}}</td>
	<td>{{$servicio->turno}}</td>
	<td>NA</td>
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
	<td>{{$servicio->placa}}</td>
	<td>{{$servicio->conductor->documento}}</td>
	<td>SI</td>
	<td>0</td>
	<td>0</td>
	<td>0</td>
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][5]['cargado']}}</td><!--doc cedula!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][1]['cargado']}}</td><!--doc licencia!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][4]['cargado']}}</td> <!--doc PLANILLA DE SS!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][7]['cargado']}}</td> <!--doc rut!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][16]['cargado']}}</td> <!--doc simit!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][17]['cargado']}}</td> <!--doc runt!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][18]['cargado']}}</td> <!--doc antecedentes!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][19]['cargado']}}</td> <!--doc estudios adicionales!-->
	<td>{{$documentos_vehiculo[$servicio->placa][15]['cargado']}}</td> <!--doc fotos vehiculo!-->
	<td>{{$documentos_vehiculo[$servicio->placa][8]['cargado']}}</td> <!--doc licencia de transito!-->
	<td>{{$documentos_vehiculo[$servicio->placa][9]['cargado']}}</td> <!--doc soat!-->
	<td>{{$documentos_vehiculo[$servicio->placa][10]['cargado']}}</td> <!--doc revision tecnicomecanica!-->
	<td>{{$documentos_vehiculo[$servicio->placa][13]['cargado']}}</td> <!--doc tarjeta operacion!-->
	<td>{{$documentos_vehiculo[$servicio->placa][11]['cargado']}}</td> <!--doc poliza!-->
	<td>{{$documentos_vehiculo[$servicio->placa][14]['cargado']}}</td> <!--doc revision preventiva!-->
	<td>NA</td><!--doc empresa afiliadora!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][1]['fecha_vencimiento']}}</td> <!--fecha licencia cond!-->
	<td>{{Helper::getFechasDias($documentos_conductor[$servicio->id_conductor_servicio][1]['fecha_vencimiento'])}}</td><!--dias vencimiento licencia cond!-->
	<td>{{$documentos_vehiculo[$servicio->placa][9]['fecha_vencimiento']}}</td>  <!--fecha seguro obl!-->
	<td>{{Helper::getFechasDias($documentos_vehiculo[$servicio->placa][9]['fecha_vencimiento'])}}</td><!--dias vencimiento fecha seguro obl!-->
	<td>{{$documentos_vehiculo[$servicio->placa][11]['fecha_vencimiento']}}</td> <!--fecha poliza!-->
	<td>{{Helper::getFechasDias($documentos_vehiculo[$servicio->placa][11]['fecha_vencimiento'])}}</td> <!--dias vencimiento poliza!-->
	<td>{{$documentos_vehiculo[$servicio->placa][12]['fecha_vencimiento']}}</td> <!--fecha poliza extra!-->
	<td>{{Helper::getFechasDias($documentos_vehiculo[$servicio->placa][12]['fecha_vencimiento'])}}</td><!--dias vencimiento poliza cond!-->
	<td>{{$documentos_vehiculo[$servicio->placa][13]['fecha_vencimiento']}}</td> <!--fecha tarjeta operacion!-->
	<td>{{Helper::getFechasDias($documentos_vehiculo[$servicio->placa][13]['fecha_vencimiento'])}}</td><!--dias vencimiento tarjeta op!-->
	<td>{{$documentos_vehiculo[$servicio->placa][10]['fecha_vencimiento']}}</td> <!--fecha tecnicomecanica!-->
	<td>{{Helper::getFechasDias($documentos_vehiculo[$servicio->placa][10]['fecha_vencimiento'])}}</td><!--dias vencimiento tecnicomecanica!-->
	<td>{{$documentos_vehiculo[$servicio->placa][14]['fecha_vencimiento']}}</td> <!--fecha preventiva!-->
	<td>{{Helper::getFechasDias($documentos_vehiculo[$servicio->placa][14]['fecha_vencimiento'])}}</td><!--dias vencimientos preventiva!-->

	<tr>
	@endforeach
</tbody>
</table>

