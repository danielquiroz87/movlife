
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Pre Servicios</li>
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
            <h1>Pre Servicios Web</h1>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Pre Servicios</h3>
              <!-- /.card-header -->
            <form>

              <div class="row">

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Inicial:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_inicial]" value="{{$filtros['fecha_inicial']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Final:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
                  </div>

                 
                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Clientes:</strong></label>
                         <select name="filtros[cliente]" class="form-control">
                            <?php echo Helper::selectClientes($filtros['cliente']) ?>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Sedes:</strong></label>
                         <select name="filtros[uri_sede]" class="form-control">
                            <?php echo Helper::selectSedes($filtros['uri_sede']) ?>
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
                      <th>Cliente Email / Celular</th>
                      <th>Pasajero</th>
                      <th>Fechas Solicitud / Servcicio</th>
                      <th>Dirección Recogida</th>
                      <th>Dirección Destino</th>
                      <th>Uri / Sede</th>
                      <th>Tipo Servicio</th>
                      <th>Tipo Viaje</th>
                      <th>Hora Recogida / Hora Regreso</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($servicios as $servicio)
                    <tr>
                     <td>
                        @if($servicio->cliente)
                           {{$servicio->cliente->documento}},<br/>{{$servicio->cliente->razon_social}} 
                        @else
                          {{$servicio->cliente_documento}},{{$servicio->cliente_nombres}} {{$servicio->cliente_apellidos}}
                        @endif
                      </td>
                     <td style="max-width: 120px">{{$servicio->cliente_email}},<br/>{{$servicio->cliente_celular}}</td>
                    
                      <td style="max-width: 120px">
                        @if($servicio->pasajero)
                           {{$servicio->pasajero->documento}},<br/>{{$servicio->pasajero->razon_social}}
                           {{$servicio->pasajero->email_contacto}},<br/>{{$servicio->pasajero->celular}}
                        @else
                          {{$servicio->pasajero_documento}},<br/>{{$servicio->pasajero_nombres}} {{$servicio->pasajero_apellidos}}<br/>
                          {{$servicio->pasajero_email}},{{$servicio->pasajero_celular}}
                        @endif
                      </td>
                     
                     <td>Fecha Sol:{{date('d/m/y',strtotime($servicio->fecha_solicitud))}}<br/>
                        Fecha Serv:{{date('d/m/y',strtotime($servicio->fecha_servicio))}}

                     </td>
                     <td >{{$servicio->origen}}</td>
                     <td >{{$servicio->destino}}</td>
                     <td  >
                      @if($servicio->uri_sede)
                        {{$servicio->sede->nombre}}
                      @else
                      N/A
                      @endif
                    </td>
                    <td>{{$servicio->tiposervicio->nombre}}</td>
                    <td>{{$servicio->tipoViaje()}}</td>

                     <td>HR:{{$servicio->hora_recogida}}<br/>
                         HS:{{$servicio->hora_regreso}}
                     </td>
                     
                     <td>
                        <a class="text-default mr-2 duplicar" href="{{route('servicios.from.preservicio', $servicio->id)}}" title="Crear Servicio" >
                        <i class="nav-icon i-Data-Copy font-weight-bold"></i></i></a>

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
