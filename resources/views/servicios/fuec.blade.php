<!DOCTYPE html>
<html>
<head>
	<title>Formato Fuec</title>
	<style type="text/css">
		body{
			font-size:14px;
			max-width: 800px; 
			margin: 20px
		}
		
	</style>
</head>
<body style="">
<table  border="0" style="border-collapse: collapse" width="100%" >
<tr>
<th width="50%">
	<p style="margin: 10px">
	<img src="https://app.movlife.co/images/min_transporte.png" >
	</p>
</th>
<th width="50%">
	<p style="margin: 10px;text-align: center;">
	<img src="https://app.movlife.co/images/movlife.png" height="44">
	</p>
</th>
</tr>
</table>
<br/>
<div>
<center>
<strong>FORMATO ÚNICO DE EXTRACTO DEL CONTRATO DEL SERVICIO PÚBLICO DE TRANSPORTE TERRESTRE AUTOMOTOR ESPECIAL</strong>
<br/><br/>
<span style="color: red"><strong>No. {{$consecutivo}}</strong></span>
</center>

</div>

<div>
	<p>
		RAZÓN SOCIAL DE LA EMPRESA DE TRANSPORTE ESPECIAL: MOVLIFE S.A.S<br/>
		NIT. 901.184.493-5<br/>
		CONTRATO No. 0974<br/>
		CONTRATANTE: {{$servicio->cliente->razon_social}}<br/>
		NIT/CC. {{$servicio->cliente->documento}}<br/>
		OBJETO CONTRATO: CONTRATO PARA TRANSPORTE DE USUARIOS DEL SERVICIO DE SALUD<br/>
		ORIGEN-DESTINO: ORIGEN:  {{$servicio->origen}} / DESTINO: {{$servicio->destino}}<br/>
		con retorno a lugar de origen<br/>
		CONVENIO DE COLABORACIÓN: SPECIAL CAR PLUS TRANSPORTE S.A.S<br/>
	</p>
</div>

<div style="margin: 10px">
	<center><strong>VIGENCIA DEL CONTRATO</strong></center>
</div>
<table  width="100%" border="1" style="border-collapse: collapse; text-align: center;">
	<tbody>
	
	<tr>
	<td style="text-align: left;width: 300px"><span style="margin:5px">FECHA INICIAL</span></td><td>DIA<br/>{{$dia}}</td>
	<td>MES<br/>{{$mes}}</td><td>AÑO<br/>{{$year}}</td>
	</tr>
	<tr>
	<td style="text-align: left;width: 300px"><span style="margin:5px">FECHA VENCIMIENTO</span></td><td>DIA<br/>{{$dia}}</td><td>MES<br/>{{$mes}}</td><td>AÑO<br/>{{$year}}</td>
	</tr>
	</tbody>
</table>

<div style="margin: 5px">
<br/>
	<center><strong>CARACTERÍSTICAS DEL VEHÍCULO</strong></center>
</div>

<table width="100%" border="1" style="border-collapse: collapse; text-align: center; 
;">
	<thead>

		<tr>
			<th><strong><span style="margin:5px;display: block;">PLACA<br/></span></strong></th>
			<th ><strong><span style="margin:5px;display: block;">MODELO<br/></span></strong></th>
			<th ><strong><span style="margin:5px;display: block;">MARCA<br/></span></strong></th>
			<th ><strong><span style="margin:5px;display: block;">CLASE<br/></span></strong></th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td><span style="margin:5px;display: block;">{{$servicio->vehiculo->placa}}</span></td>
		<td><span style="margin:5px;display: block;">{{$servicio->vehiculo->modelo}}</span></td>
		<td><span style="margin:5px;display: block;">{{$servicio->vehiculo->marca->nombre}}</span></td>
		<td><span style="margin:5px;display: block;">{{strtoupper($servicio->vehiculo->clase->nombre)}}</span></td>

	</tr>
	<tr>
		<td colspan="2"><strong><span style="margin:5px;display: block;">NÚMERO INTERNO NÚMERO</span></strong></td>
		<td colspan="2"><strong><span style="margin:5px;display: block;">NÚMERO INTERNO NÚMERO TARJETA DE OPERACIÓN</span></strong></td>
	</tr>
	<tr>
		<td colspan="2"><span style="margin:5px;display: block;">{{$servicio->vehiculo->codigo_interno}}</span></td>
		<td colspan="2"><span style="margin:5px;display: block;">{{ ($documentos[$servicio->vehiculo->placa][13]['numero'])}}</span></td>
	</tr>
	
	</tbody>
</table>
<table width="100%" border="1" style="border-collapse: collapse; text-align: center; border-top: none ">
	<tr>
		<td><span style="margin:5px;display: block;">DATOS DEL CONDUCTOR 1</span></td>
		<td><span style="margin:5px;display: block;">NOMBRES Y APELLIDOS {{$servicio->conductorServicio->nombres}} {{$servicio->conductorServicio->apellidos}}</span></td>
		<td><span style="margin:5px;display: block;">No. CÉDULA {{$servicio->conductorServicio->documento}}</span></td>
		<td><span style="margin:5px;display: block;">No. LICENCIA DE CONDUCCIÓN <br/> {{$documentos_conductor[$servicio->conductorServicio->id][1]['numero']}}</span>
		</td>
		<td><span style="margin:5px;display: block;">VIGENCIA <br/>{{$documentos_conductor[$servicio->conductorServicio->id][1]['fecha_vencimiento']}}</span>
		</td>
	</tr>
	<tr>
		<td><span style="margin:5px;display: block;">DATOS DEL CONDUCTOR 2</span></td>
		<td><span style="margin:5px;display: block;">NOMBRES Y APELLIDOS </span></td>
		<td><span style="margin:5px;display: block;">No. CÉDULA </span></td>
		<td><span style="margin:5px;display: block;">No. LICENCIA DE CONDUCCIÓN  </span>
		</td>
		<td><span style="margin:5px;display: block;">VIGENCIA</span></td>
	</tr>

	<tr>
		<td><img src="{{$qr}}" alt="QR" /></td>
		<td colspan="2"><span style="margin:5px;display: block;">BARRIO LAS PALMAS CASA 164 B <br/> Teléfono: +57 3118764460 <br/>claudia.ﬂorez@movlife.co<br/> www.rapidexpres.com<br/> NARIÑO</span></td>
		<td colspan="2"><span style="margin:5px;display: block;">Firma Digital Representante Legal<br/>
		Ley 2364 de 2012 Art 5: Efectos jurídicos de la ﬁrma electrónica. La ﬁrma electrónica tendrá la misma validez y efectos jurídicos que la ﬁrma.</span></td>
	</tr>
</table>
<br/>
<center><span style="margin-top: 5px">GENERADO POR SR(A) MARIA FERNANDA NIñO PEREZ - CARGO: - EL 24 DE MAYO DE 2023 A LAS 08:56 PM DESDE EL SISTEMA FUEC DE MOVLIFE S.A.S</span></center>
</body>
</html>
