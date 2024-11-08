<style>
    th, td{
        padding: 8px;
    }
    .center{
        text-align: center;
    }
    .morado{
        background-color: #4c379a;
        color:white;
    }
</style>
<center>
<table border="1" style="border-collapse: collapse;border-spacing: 4px;">
	<tbody>
		<tr>
			<td colspan="3"><img src="https://app.movlife.co/images/movlife.png" height="44"></td>
            <td colspan="3" class="center">FORMATO DE SOLICITUD DE ANTICIPOS</td>
            <td colspan="3" class="center">
            FR-04-015-01<br/>
            12/02/2021<br/>
            Página 1 de 1
            </td>
		</tr>
        <tr>
			<td colspan="9" class="center morado"></td>
		</tr>
		<tr>
			<td>Fecha De Solicitud</td>
			<td>Dia</td>
			<td>{{date('d',strtotime($anticipo->created_at))}}</td>
			<td>Mes</td>
			<td>{{date('m',strtotime($anticipo->created_at))}}</td>
			<td>Año</td>
			<td>{{date('Y',strtotime($anticipo->created_at))}}</td>
			<td>Anticipo ID</td>
			<td>{{$anticipo->id}}</td>

           
		</tr>
		<tr>
			<td colspan="3">Nombre de Quien Hace La Solicitud</td>
			<td colspan=2>{{$anticipo->coordinador->nombres}} {{$anticipo->coordinador->apellidos}}</td>
			<td>Cliente:</td>
			<td colspan="3">{{$anticipo->cliente->razon_social}}</td>
		</tr>
        <tr>
			<td colspan="3">Cargo de Quien Hace La Solicitud</td>
			<td colspan=6>Coordinadora De Operaciones</td>
		</tr>
		@if($anticipo->conductorServicio)	
		<tr>
			<td colspan="9" class="center morado">CONDUCTOR SERVICIO </td>
		</tr>
        <tr>
			<td colspan="3">Nombre</td>
			<td>{{$anticipo->conductorServicio->nombres}} {{$anticipo->conductorServicio->apellidos}}</td>
			<td>CEDULA </td>
			<td>{{$anticipo->conductorServicio->documento}} </td>
			<td>TELEFONO </td>
			<td colspan="2">{{$anticipo->conductorServicio->celular}} </td>
		</tr>
		@endif
        <tr>
			<td colspan="9" class="center morado">CONDUCTOR QUE SOLICITA EL ANTICIPO </td>
		</tr>
        <tr>
			<td colspan="3">Nombre</td>
			<td>{{$anticipo->conductor->nombres}} {{$anticipo->conductor->apellidos}}</td>
			<td>CEDULA </td>
			<td>{{$anticipo->conductor->documento}} </td>
			<td>TELEFONO </td>
			<td colspan="2">{{$anticipo->conductor->celular}} </td>
		</tr>
		
        <tr>
			<td colspan="3">Concepto Anticipo</td>
			<td>&nbsp;</td>
			<td>TIEMPO DE PAGO EN MESES	 </td>
			<td colspan=5>&nbsp;</td>
		</tr>
        <tr>
			<td colspan="3">Valor Servicio Al Cliente</td>
			<td colspan=3>$</td>
			@if($anticipo->sevicio_id>0)
			<td colspan=3>{{number_format($servicio->valor_cliente,0)}}</td>
			@else
			<td colspan=3>{{number_format($anticipo->valor_cliente,0)}}</td>

			@endif
		</tr>
        <tr>
			<td colspan="3">Valor Del Anticipo</td>
			<td colspan=3>$</td>
			<td colspan=3>{{number_format($anticipo->valor,0)}}</td>
		</tr>
        <tr>
			<td colspan="9" class="center morado" >PERSONA A LA QUE SE LE PAGA (SOLO APLICA EN CASO DE QUE SEA UN TERCERO AL QUE SE LE HACE EL PAGO) </td>
		</tr>
        <tr>
			<td colspan="3">Nombre</td>
			<td>{{$anticipo->conductor->nombres}} {{$anticipo->conductor->apellidos}}</td>
			<td>CEDULA </td>
			<td>{{$anticipo->conductor->documento}}</td>
			<td>TELEFONO </td>
			<td colspan="2">{{$anticipo->conductor->celular}}</td>
		</tr>
        <tr>
			<td colspan="3">Numero de Cuenta</td>
			<td>{{$cuenta_bancaria->numero}}</td>
			<td>TIPO DE CUENTA  </td>
			<td>{{$cuenta_bancaria->tipo}}</td>
			<td colspan="3">{{$cuenta_bancaria->banco}}</td>
			
		</tr>
        <tr>
			<td colspan="4">OBSERVACIONES: {{$anticipo->observaciones}} </td>
			<td>Servicio Id</td><td>{{$anticipo->servicio_id}}</td>
			<td colspan=2>PreServicio Id</td><td>&nbsp;{{$anticipo->preservicio_id}}</td>
		</tr>

        <tr>
			<td colspan="5">Elaboró: {{$coordinador->nombres}} {{$coordinador->apellidos}}<br/>
            Coordinador(a) De Operaciones	
            </td>
			
			<td colspan=4>
            Aprobó: Claudia Florez<br/>
            Directora General
            </td>
		</tr>
	</tbody>
</table>
</center>