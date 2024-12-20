<?php
$tipo_viaje=array(1=>'Ida',2=>'Ida y Regreso',3=>'Regreso',4=>'Multiviaje');
?>
<html>
<meta charset="UTF-8">
<title>Excel</title>
<body>
<table  border="1" style="border-collapse: collapse" >
<tr>
<th>PRESERVICIO ID</th>
<th>CONSECUTIVO</th>
<th>FECHA DE SOLICITUD DEL SERVICIO</th>
<th>FECHA DE PRESTACION DEL SERVICIO</th>
<th>TIPO DE SERVICIO</th>
<th>USUARIO SISTEMA</th>
<th>EDUCADOR / COORDINADOR</th>
<th>SEMANA</th>
<th>PERSONA A TRANSPORTAR</th>
<th>DOCUMENTO PERSONA A TRANSPORTAR	</th>
<th>TELEFONO PACIENTE	</th>
<th>CLIENTE	</th>
<th>UR / SEDE</th>
<th>CIUDAD DEL PACIENTE</th>
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
<th>HORA DE LA CITA-INFUSION-EXAMEN	</th>
<th>HORA DE TERMINACION DE LA CITA/INFUSION/ EXAMEN	 </th>
<th>TERAPIA	</th>
<th>PROGRAMA 	 </th>
<th>ESTADO SERVICIO</th>
<th>CEDULA CONDUCTOR PRINCIPAL 	</th>
<th>CONDUCTOR PRINCIPAL (A QUIEN SE LE PAGA)</th>
<th>CONDUCTOR QUE HACE EL SERVICIO 	</th>
<th>TELEFONO CONDUCTOR 	</th>
<th>PERSONA AUTORIZADA PARA PAGO	</th>
<th>CEDULA -  AUTORIZADA PARA PAGO	 </th>
<th>COSTO / VALOR CONDUCTOR	 </th>
<th>DESCUENTO PRESTAMO	 </th>
<th>PRECIO ALIMENTACIÓN CONDUCTOR 	</th> 
<th>TOTAL CON DESCUENTO INCLUIDO </th>	 
<th>VALOR CLIENTE	 </th>
<th>AUXILIAR</th>
<th>VALOR AUXILIAR</th>
<th>FACTURA	 </th>
<th>TIPO ANTICIPO</th>
<th>ANTICIPO	 </th>
<th>NUMERO PAGO	</th>
<th>FECHA DE PAGO	</th>
<th>BANCO	 </th>
<th>VALOR BANCO  Y/O ANTICIPO </th>	 
<th>SALDO </th>
<th>NO ORDEN DE COMPRA 	 </th>
<th>DOC CONTABLE	</th>
<th>OBSERVACIONES SERVICIO	</th>
<th>COMENTARIOS SERVICIO</th>
<th>OBSERVACIONES CONTABILIDAD</th>
<th>PLACA</th>
<th>CEDULA PLACA</th>
<th>USO VEHICULO</th>
<th>MARCA VEHICULO</th>
<th>MODELO VEHICULO</th>
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
<th>POLIZA RCC</th>
<th>POLIZA RCE</th>
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
<th>FECHA REVISIÓN PREVENTIVA</th>
<th>DIAS PARA VENCIMIENTO</th>
<th>FUEC</th>
<th>FUEC FECHA INICIAL</th>
<th>FUEC FECHA VENCIMIENTO</th>

</tr>
<tbody>
	@foreach ($servicios as $servicio)
	<tr>
	<?php 
	
	$documentos_conductor=Helper::getDocumentosConductor($servicio->id_conductor_servicio);
	$documentos_vehiculo=Helper::getDocumentosVehiculo($servicio->placa);
	?>
	<td>{{$servicio->preservicio_id}}</td>
	<td>{{$servicio->id}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td>{{$servicio->fecha_servicio}}</td>
	<td><?php echo $tipo_servicios[$servicio->tipo_servicio-1]->nombre;?></td>
	<td>
		@if($servicio->user_id>0)
			{{Helper::getUsername($servicio->user_id);}}
		@else
			NA
		@endif
	</td>
	<td>{{$servicio->educador_coordinador}}</td>

	<td>{{$servicio->semana}}</td>
	
	<td>
		@if($servicio->pasajero)
             {{$servicio->pasajero->nombres}} {{$servicio->pasajero->apellidos}}
        @else
            N/A
        @endif		
		
	</td>

	<td>
		@if($servicio->pasajero)
			{{$servicio->pasajero->documento}}
		@else
		 N/A
		@endif
	</td>
	
	<td> 
		@if($servicio->pasajero)
			{{$servicio->pasajero->telefono}}
		@else
		 N/A
		@endif
	</td>
	
	<td> 
		@if($servicio->cliente)
        	{{$servicio->cliente->documento}},{{$servicio->cliente->razon_social}}
        @else
        	N/A
        @endif
	</td>
	
	<td> 
		@if( (int) $servicio->uri_sede>0 )
			{{$servicio->sede->nombre}}
		@else
		N/A
		@endif
	</td>
	
	<td> 	
		@if( $servicio->pasajero )
			@if($servicio->pasajero->direccion)

			 	<?php echo Helper::getCiudad($servicio->pasajero->direccion->ciudad_id) ?>
				
			@else
			 N/A
			@endif
		@else
			N/A
		@endif
	</td>
	
	<td> 
		@if( $servicio->pasajero )
			{{$servicio->pasajero->codigo}}
		@else
			N/A
		@endif
	</td>
	
	<td>{{$servicio->origen}}</td> 
	<td>{{$servicio->barrio}}</td>
	<td>{{$servicio->origen}}</td> 
	<td>{{$servicio->destino}}</td> 
	<td>{{$servicio->kilometros}}</td> 
	<td>{{$servicio->tiempo}}</td> 
	<td>{{$servicio->hora_recogida}}</td>
	<td>{{$tipo_viaje[$servicio->tipo_viaje]}}</td> 
	<td> 
		@if( $servicio->turno!="" )
			{{$servicio->turno}}
		@else
			N/A
		@endif
	</td>
	
	<td>
		@if( $servicio->hora_infusion_inicial!="" )
			{{$servicio->hora_infusion_inicial}}
		@else
			N/A
		@endif
	</td>

	<td> 
		@if( $servicio->hora_infusion_final!="" )
			{{$servicio->hora_infusion_final}}
		@else
			N/A
		@endif
	</td>
	<td> 
		@if( $servicio->terapia!="" )
			{{$servicio->terapia}}
		@else
			N/A
		@endif
	</td>
	<td> 
		@if( $servicio->programa!="" )
			{{$servicio->programa}}
		@else
			N/A
		@endif
	</td>
	<td>{{Helper::getEstadoServicio($servicio->estado)}}</td> 
	<td>{{$servicio->conductor->documento}}</td> 
	<td>{{$servicio->conductor->nombres}} {{$servicio->conductor->apellidos}}</td> 
	<td>{{$servicio->conductorServicio->nombres}}</td> 
	<td>{{$servicio->conductorServicio->celular}}</td> 
	<td>{{$servicio->conductor->nombres}} {{$servicio->conductor->apellidos}}</td> 
	<td>{{$servicio->conductor->documento}}</td> 
	<td>{{number_format($servicio->valor_conductor,2,',','.')}}</td> 
	<td>{{number_format($servicio->descuento,2,',','.')}}</td> 
	<td>0</td>
	<td> 
		<?php $total_con_descuento=$servicio->valor_conductor-$servicio->descuento;?>
		{{number_format($total_con_descuento,2,',','.')}}
	</td>
	<td>{{number_format($servicio->valor_cliente,2,',','.')}}</td> 
	<td>{{$servicio->auxiliar}}</td>
	<td>{{$servicio->valor_auxiliar}}</td>
	<td>{{$servicio->nro_factura}}</td> 
	<td>
		
	</td> 
	<td>
	@if($servicio->tipo_anticipo==1)
			{{Helper::getTipoAnticipo($servicio->tipo_anticipo)}} - {{Helper::getIdAnticipo($servicio->id)}}
		@else
				@if($servicio->conductor->tipo_vinculacion==1)	
					{{Helper::getTipoAnticipo(2)}}
				@else
					{{Helper::getTipoAnticipo($servicio->tipo_anticipo)}}
				@endif
		@endif
	</td> 
	<td>{{$servicio->nro_pago}}</td> 
	<td>{{$servicio->fecha_pago}}</td>
	<td>{{$servicio->banco}}</td>
	<td>{{number_format($servicio->valor_banco,2,',','.')}}</td>
	<td>
		<?php if($servicio->valor_banco>0){
			$saldo=($servicio->valor_conductor+$servicio->alimentacion)-$servicio->valor_banco;
		}else{
			$saldo=$servicio->saldo;
		}?>
		
	{{$saldo}}
	</td><!--Saldo-->
	<td>{{$servicio->orden_compra}}</td>
	<td>{{$servicio->doc_contable}}</td>
	<td>{{$servicio->observaciones}}</td>
	<td>{{$servicio->comentarios}}</td>
	<td>{{$servicio->observaciones_contabilidad}}</td>
	<td>{{$servicio->placa}}</td>
	<td>{{$servicio->conductor->documento}}</td>
	<td>{{Helper::getUsoVehiculo($servicio->placa)}}</td>
	<td>{{Helper::getMarcaVehiculo($servicio->placa)}}</td>
	<td>{{Helper::getModeloVehiculo($servicio->placa)}}</td>
	
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][5]['cargado']}}</td><!--doc cedula!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][1]['cargado']}}</td><!--doc licencia!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][4]['cargado']}}</td> <!--doc PLANILLA DE SS!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][7]['cargado']}}</td> <!--doc rut!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][16]['cargado']}}</td> <!--doc simit!-->
	<td>{{$documentos_vehiculo[$servicio->placa][17]['cargado']}}</td> <!--doc runt!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][18]['cargado']}}</td> <!--doc antecedentes!-->
	<td>{{$documentos_conductor[$servicio->id_conductor_servicio][19]['cargado']}}</td> <!--doc estudios adicionales!-->
	<td>{{$documentos_vehiculo[$servicio->placa][15]['cargado']}}</td> <!--doc fotos vehiculo!-->
	<td>{{$documentos_vehiculo[$servicio->placa][8]['cargado']}}</td> <!--doc licencia de transito!-->
	<td>{{$documentos_vehiculo[$servicio->placa][9]['cargado']}}</td> <!--doc soat!-->
	<td>{{$documentos_vehiculo[$servicio->placa][10]['cargado']}}</td> <!--doc revision tecnicomecanica!-->
	<td>{{$documentos_vehiculo[$servicio->placa][13]['cargado']}}</td> <!--doc tarjeta operacion!-->
	<td>{{$documentos_vehiculo[$servicio->placa][11]['cargado']}}</td> <!--doc poliza!-->
	<td>{{$documentos_vehiculo[$servicio->placa][12]['cargado']}}</td> <!--doc poliza!-->
	<td>{{$documentos_vehiculo[$servicio->placa][14]['cargado']}}</td> <!--doc revision preventiva!-->
	<td>{{Helper::getEmpresaAfiliadora($servicio->placa)}}</td><!--doc empresa afiliadora!-->
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
	<td>FUEC</td>
	<td>FECHA FUEC INICIAL</td>
	<td>FUEC FECHA VENCIMIENTO</td>

	
	</tr>
	@endforeach
</tbody>
</table>
</body>
</html>

