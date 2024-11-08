<table border="1" style="border-collapse: collapse" id="hidden_column_table" class="table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
    <thead>
    <tr>
        <th>Id</th>
        <th>Fecha</th>
        <th>Servicio Id</th>
        <th>Fecha Servicio</th>
        <th>Pasajero</th>
        <th>Cliente</th>
        <th>Conductor</th>
        <th>Valor Cliente</th>
        <th>Valor Conductor</th>
        <th>Valor Anticipo</th>
        <th>Total Abonos</th>
        <th>Total Restante</th>
        <th>Estado</th>
   
    </tr>
    </thead>
    <tbody>
    @foreach ($anticipos as $anticipo)
    <?php $totalAbonos=Helper::getTotalAbonosAnticipos($anticipo->id);?>
    <tr>
        <td>{{$anticipo->id}}</td>
        <td>{{$anticipo->created_at}}</td>
        
        @if($anticipo->servicio_id>0)

        <td>{{$anticipo->servicio->id}}</td>
        <td>{{$anticipo->servicio->fecha_servicio}}</td>
        <td>{{$anticipo->servicio->pasajero->nombres}} {{$anticipo->servicio->pasajero->apellidos}}</td>
        <td>{{$anticipo->servicio->cliente->razon_social}}</td>
        <td>{{$anticipo->conductor->nombres}} {{$anticipo->conductor->apellidos}}</td>
        <td>${{($anticipo->servicio->valor_cliente)}}</td>
        <td>${{($anticipo->servicio->valor_conductor)}}</td>

        @else

        <td>SIN ASIGNAR</td>
        <td>NA</td>
        <td>NA</td>
        <td>{{$anticipo->cliente->razon_social}}</td>
        <td>{{$anticipo->conductor->nombres}} {{$anticipo->conductor->apellidos}}</td>
        <td>${{($anticipo->valor_cliente)}}</td>
        <td>${{($anticipo->valor)}}</td>

        @endif
        <td>${{($anticipo->valor)}}</td>
        <td>${{($totalAbonos)}}</td>
        <td>${{($anticipo->valor-$totalAbonos)}}</td>
        <td>
        @if($anticipo->estado==2)
        Pagado
        @else
        Activo
        @endif
        </td>
        
    </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
    

    </tr>
    </tfoot>
</table>