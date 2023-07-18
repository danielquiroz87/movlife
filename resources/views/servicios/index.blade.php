
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Servicios</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>
 @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif
  @if ($message = Session::get('flash_bad_message'))
    <div class="alert alert-danger alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif
  <div class="row">
          <div class="col-md-12">
            <h1>Servicios</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-success" href="{{route('servicios.new')}}">Nuevo</a>&nbsp;&nbsp;
                    <a class="btn btn-primary" href="{{route('servicios.descargar')}}" target="_blank" >Descargar</a>&nbsp;&nbsp;
                    <a class="btn btn-success" href="{{route('servicios.importar')}}" target="_blank" >Importar</a>
            </div>
            
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Servicios</h3>
              <!-- /.card-header -->
            <form>

              <div class="row">

                 <div class="col-md-3 form-group mb-3">
                    <label><strong>Estado Servicio:</strong></label>
                         <select name="filtros[estado]" class="form-control">
                            <option value="">Todos</option>
                            <option value="1" @if ($filtros['estado']==1) selected="selected" @endif>Iniciado</option>
                            <option value="2" @if ($filtros['estado']==2) selected="selected" @endif>En Proceso</option>
                            <option value="3" @if ($filtros['estado']==3) selected="selected" @endif>Cumplido</option>
                            <option value="4" @if ($filtros['estado']==4) selected="selected" @endif>Cancelado</option>

                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Inicial:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_inicial]" value="{{$filtros['fecha_inicial']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Final:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Pasajero:</strong></label>
                         <input type="text" class="form-control" name="filtros[pasajero]" value="{{$filtros['pasajero']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Clientes:</strong></label>
                         <select name="filtros[cliente]" class="form-control">
                            <?php echo Helper::selectClientes($filtros['cliente']) ?>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Conductor:</strong></label>
                         <select name="filtros[conductor]" class="form-control">
                            <option value="">Seleccione</option>
                            <?php echo Helper::selectConductores($filtros['conductor']) ?>
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
                     <td>
                        @if($servicio->cliente)
                           {{$servicio->cliente->documento}},{{$servicio->cliente->razon_social}} 
                        @else
                          N/A
                        @endif
                      </td>
                     <td>{{$servicio->placa}}</td>
                     <td>{{$servicio->conductor->nombres}}</td>
                     <td>
                        @if($servicio->pasajero)
                          {{$servicio->pasajero->nombres}} {{$servicio->pasajero->apellidos}}
                        @else
                         N/A
                        @endif
                        
                      </td>
                     <td>{{$servicio->fecha_servicio}}</td>
                     <td  style="max-width: 100px">{{$servicio->origen}}</td>
                     <td  style="max-width: 100px">{{$servicio->destino}}</td>
                     <td>{{$servicio->hora_recogida}}</td>
                     <td>{{$servicio->hora_estimada_salida}}</td>
                     <td>{{$servicio->valor_cliente}}</td>
                     <td>
                        <a class="text-success mr-2" href="{{route('servicios.edit',['id'=>$servicio->id])}}" title="Editar">
                          <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                        </a>
                        <a class="text-default mr-2 duplicar" href="{{route('servicios.edit', $servicio->id)}}" title="Duplicar Servicio" >
                        <i class="nav-icon i-Data-Copy font-weight-bold"></i></i></a>
                        
                        <a class="text-danger mr-2 eliminar" href="{{route('servicios.delete', $servicio->id)}}" title="Eliminar" >
                        <i class="nav-icon i-Close-Window font-weight-bold"></i></i>
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
   				    	<?php echo $servicios->withQueryString()->links(); ?>
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
<script type="text/javascript">
 $(document).ready(function(){
 	$('.eliminar').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-delete-form').attr('action',url);

 		Swal
	    .fire({
	        title: "Eliminar",
	        text: "Está seguro de eliminar este registro?",
	        icon: 'warning',
	        showCancelButton: true,
	        confirmButtonText: "Sí, eliminar",
	        cancelButtonText: "Cancelar",
	    })
	    .then(resultado => {
	        if (resultado.value) {
	            // Hicieron click en "Sí"
 				$('#user-delete-form').submit();
	        } else {
	            // Dijeron que no
	            console.log("*NO se elimina la venta*");
	        }
	    });



 	})
 })
</script>
@endsection
