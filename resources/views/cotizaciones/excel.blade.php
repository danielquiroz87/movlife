<table border="1" style="border-collapse: collapse" id="hidden_column_table" class="table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Cliente</th>
                      <th>Fecha Creación</th>
                      <th>Fecha Cotización</th>
                      <th>Fecha Vencimiento</th>
                      <th>Fecha Servicio</th>
                      <th>Hora Recogida</th>
                      <th>Dirección Recogida</th>
                      <th>Dirección Destino</th>
                      <th>Valor</th>
                      <th>Cantidad</th>
                      <th>Total</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($cotizaciones as $cotizacion)
                    <tr>
                    <td>{{$cotizacion->id}}</td>
                     <td>{{$cotizacion->cliente->documento}}, {{$cotizacion->cliente->nombres}} {{$cotizacion->cliente->apellidos}}</td>
                     <td>{{$cotizacion->created_at}}</td>
                     <td>{{$cotizacion->fecha_cotizacion}}</td>
                     <td>{{$cotizacion->fecha_vencimiento}}</td>
                     <td>{{$cotizacion->fecha_servicio}}</td>
                     <td>{{$cotizacion->hora_recogida}}</td>
                     <td>{{$cotizacion->direccion_recogida}}</td>
                     <td>{{$cotizacion->direccion_destino}}</td>
                     <td>{{$cotizacion->valor}}</td>
                     <td>{{$cotizacion->cantidad}}</td>
                     <td>{{$cotizacion->total}}</td>
                    </tr>
                  	@endforeach
                  </tbody>
                  <tfoot>
                  	<tr>
                  	

                  	</tr>
                  </tfoot>
                </table>