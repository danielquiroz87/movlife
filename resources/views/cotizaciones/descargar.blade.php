<?php $total=0;$cantidad_total=0?>
<html>
	<head>
	<title></title>
	<style>
	table{
		font-size:12px !important;
		text-align: center;
	}
    th, td{
        padding: 8px;
    }
    .center{
        text-align: center;
    }
	.green{
		background-color: #00CC99;
		color: white;
		text-align: center;
		-webkit-print-color-adjust:exact;

	}
    .morado{
        background-color: #4c379a;
        color:white;
		text-align: center;
		-webkit-print-color-adjust:exact;

    }
</style>
	</head>
<body>
<div>
<center>
<table border="1" style="border-collapse: collapse;border-spacing: 4px;">
	<tbody>
		<tr>
			<td><img src="https://app.movlife.co/images/movlife.png" height="44"></td>
            <td colspan="4" class="center">FORMATO DE COTIZACIÓN</td>
            <td  class="center">
            FR-03-001-00<br/>
            12/02/2021<br/>
            Página 1 de 1
            </td>
		</tr>
		<tr>
			<td>Nit</td>
			<td colspan=5>901184493-5</td>
		</tr>
		<tr>
			<td>Dirección:</td>
			<td colspan="5">Dg. 182 # 20-91 Local 235 A (Bogotá D.C)</td>
		</tr>
		<tr>
			<td>Contacto</td>
			<td colspan=5>3176668605-3132513608-3118764460</td>
		</tr>
        <tr>
			<td colspan="6" class="center morado">INFORMACIÓN DEL CLIENTE </td>
		</tr>
        <tr>
			<td class="green">NIT</td>
			<td class="green">CLIENTE</td>
			<td class="green">CONTACTO</td>
			<td class="green">TELÉFONO</td>
			<td colspan=2 class="green">COTIZACIÓN #</td>
		</tr>
		<tr>
			<td >{{$cotizacion->cliente->documento}}</td>
			<td >{{$cotizacion->cliente->razon_social}}</td>
			<td >{{$cotizacion->contacto_nombres}}</td>
			<td >{{$cotizacion->contacto_telefono}}</td>
			<td colspan=2 >{{$cotizacion->id}}</td>
		
		</tr>
		<tr>
			<td class="green">EMAIL</td>
			<td class="green">DIRECCIÓN</td>
			<td class="green">FECHA COTIZACIÓN</td>
			<td class="green">FECHA DE VENCIMIENTO</td>
			<td colspan=2 class="green">FORMA DE PAGO #</td>
		</tr>
		<tr>
			<td >{{$cotizacion->contacto_email}}</td>
			<td >{{$cotizacion->cliente->direccion->direccion1}}</td>
			<td >{{$cotizacion->fecha_cotizacion}}</td>
			<td >{{$cotizacion->fecha_vencimiento}}</td>
			<td colspan=2 >{{$cotizacion->forma_pago}}</td>
		</tr>
		<tr>
			<td class="morado">ITEM</td>
			<td class="morado">DESCRIPCIÓN</td>
			<td class="morado">CANTIDAD</td>
			<td class="morado">VALOR UNITARIO</td>
			<td class="morado">TOTAL</td>
			<td class="morado">TARIFARIO ID</td>



		</tr>
		<?php $rutas=$cotizacion->detalle;?>
		<?php $item=1;?>
		@foreach ($rutas as $detalle)
		<?php $total+=$detalle->total ?>
		<?php $cantidad_total+=$detalle->cantidad ?>
		<tr>
		<td >{{$item}}</td>
		<td style="font-size:11px" ><b>Descripción:</b> @if($detalle->descripcion!="")
		{{$detalle->descripcion}}
		@else
		{{$cotizacion->descripcion}}
		@endif
		<br/><br/><b>Origen:</b>{{$detalle->origen}}, 
		<br/><b>Destino:</b> {{$detalle->destino}}<br>
		<b>Tipo Viaje:</b>
		@if($detalle->tipo_viaje==1)
		SOLO IDA
		@elseif($detalle->tipo_viaje==2)
		IDA Y REGRESO
		@elseif($detalle->tipo_viaje==3)
		REGRESO
		@else
		TRAYECTO
		@endif
		<br/>
		<b>Fecha:</b>
		@if($detalle->fecha_servicio!="")
			{{$detalle->fecha_servicio}}
		@else
			{{$cotizacion->fecha_servicio}}
		@endif

		@if($detalle->hora_recogida!="")
			<br/><b>Hora Recogida:</b>{{date('h:i a',strtotime($detalle->hora_recogida))}}
			<br/><b>Hora Salida:</b>{{date('h:i a',strtotime($detalle->hora_salida))}}
		@else
		<br/>,<b>Hora Recogida:</b>{{$cotizacion->hora_recogida}}
		@endif

		</td>
		<td >{{$detalle->cantidad}}</td>
		<td >$ {{number_format($detalle->valor)}}</td>
		<td >
		${{number_format($detalle->total)}}<br/>
		</td>
		<td>{{$detalle->tarifario_id}}</td>
		</tr>
		<?php $item++;?>
		@endforeach
		<tr>
			<td colspan="3" class="morado">TOTAL</td>
			<td colspan="3">{{number_format($total)}}</td>
		</tr>
		<tr>
			<td colspan="3" class="morado">VALOR EN LETRAS</td>
			<td colspan="3"><?php echo Helper::convertiraLetras($total) ?></td>
		</tr>
		<tr>
			<td>Servicio Id</td>
			<td>{{$cotizacion->servicio_id}}</td>
			<td>Anticipo Id</td>
			<td colspan="3">{{$cotizacion->anticipo_id}}</td>
		</tr>
        <tr>
			<td colspan="2" style="text-align:left">OBSERVACIONES: </td>
			<td colspan="4" style="text-align:left">{{$cotizacion->observaciones}} </td>

		</tr>
        <tr>
			<td colspan="3">Elaboró: {{$cotizacion->user->name}}</td>
			<td>Revisado Por:</td>
			<td colspan=2>Aprobó: Claudia Florez<br/>Directora General</td>
		</tr>
		<tr>
			<td colspan="6">NOS MUEVE TU BIENESTAR</td>
		</tr>
	</tbody>
</table>
</center>
</div>
</body>

</html>