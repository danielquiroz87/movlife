
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Facturas</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>


  <div class="row">
          <div class="col-md-12">
            <h1>Facturas</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  
                    <a class="btn btn-success" href="{{route('servicios.descargar')}}">Descargar</a>
            </div>
            
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Servicios Completados</h3>
              <!-- /.card-header -->
                             <form>

              <div class="row">

                 <div class="col-md-3 form-group mb-3">
                    <label><strong>Estado Servicio:</strong></label>
                         <select name="estado" class="form-control">
                            <option value="3" >Cumplido</option>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label>&nbsp;&nbsp;&nbsp;</label><br/>
                    <button class="btn btn-success">Filtrar</button>
                  </div>
              </div>
                </form>

             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Cliente</th>
                      <th>Placa</th>
                      <th>Conductor</th>
                      <th>Pasajero</th>
                      <th>Fecha</th>
                      <th>Dirección Recogida</th>
                      <th>Dirección Destino</th>
                      <th>Hora Recogida</th>
                      <th>Hora Estimada Salida</th>
                      <th>Valor Cliente</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($servicios as $servicio)
                    <tr>
                     <td>{{$servicio->cliente->nombres}}</td>
                     <td>{{$servicio->placa}}</td>
                     <td>{{$servicio->conductor->nombres}}</td>
                     <td>{{$servicio->pasajero->nombres}}</td>
                     <td>{{$servicio->fecha_servicio}}</td>
                     <td>{{$servicio->origen}}</td>
                     <td>{{$servicio->destino}}</td>
                     <td>{{$servicio->hora_recogida}}</td>
                     <td>{{$servicio->hora_estimada_salida}}</td>
                     <td>{{$servicio->valor_cliente}}</td>
                     <td>
                        <a class="text-success mr-2" href="#" title="Editar">
                         Generar Factura
                        </a>
                       
                     </td>
                    </tr>
                  	@endforeach
                  </tbody>
                  <tfoot>
                  	<tr>
                  	

                  	</tr>
                  </tfoot>
                </table>

                <div class="d-flex justify-content-center">
   				    <div class="">
   				    	<?php echo $servicios->links(); ?>
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


        	 <form action="#" method="POST" id="user-delete-form"  >
    				{{ csrf_field() }}
      			<input type="hidden" name="id" id="userid" value="0">
   
        	</form>


@endsection

@section('bottom-js')

@endsection
