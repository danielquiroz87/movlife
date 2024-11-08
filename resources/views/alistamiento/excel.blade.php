<table border="1" style="border-collapse: collapse"  id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Placa</th>
                      <th>Conductor</th>
                      <th>Revisado</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($alistamientos as $al)
                    <tr>
                      <td>{{$al->id}}</td>
                      <td>{{$al->fecha}}</td>
                      <td>{{$al->vehiculo->placa}}</td>
                      <td>{{$al->conductor->nombres}} {{$al->conductor->apellidos}}</td>
                      <td>
                        @if($al->aprobado==1)
                          Si
                        @else
                          No
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