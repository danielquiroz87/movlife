@extends('layouts.master')
@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Anticipos</li>
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
            <h1>Anticipos</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-primary" href="{{route('anticipos.new')}}">Nuevo</a>&nbsp;&nbsp;
                    &nbsp;&nbsp;
                    <a class="btn btn-success" href="{{route('anticipos.descargar.excel')}}" target="_blank" >Descargar</a>&nbsp;&nbsp;

            </div>
            
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Anticipos</h3>

                <form>
                    <div class="row">
                     
                        <div class="col-md-2 form-group mb-2">
                            <label><strong>Fecha Inicial:</strong></label>
                            <input type="date" class="form-control" name="filtros[fecha_inicial]" value="{{$filtros['fecha_inicial']}}" >
                        </div>

                        <div class="col-md-2 form-group mb-2">
                            <label><strong>Fecha Final:</strong></label>
                            <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
                        </div>

                        <div class="col-md-1 form-group mb-3">
                          <label><strong>Sevicio Id:</strong></label>
                          <input type="text" class="form-control" name="filtros[servicio_id]" value="{{$filtros['servicio_id']}}" >
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label><strong>Conductor:</strong></label>
                                <select id='conductor' name="filtros[conductor]" class="form-control select2">
                                    <?php echo Helper::selectConductores($filtros['conductor']) ?>
                                </select>
                        </div>
                        <div class="col-md-3 form-group mb-3">
                            <label>&nbsp;&nbsp;&nbsp;</label><br/>
                            <button class="btn btn-success">Filtrar</button>
                        </div>
                    </div>
                </form>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
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
                      <th>Ver Abonos</th>
                      <th>Acciones</th>
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
                     <td>${{number_format($anticipo->servicio->valor_cliente)}}</td>
                     <td>${{number_format($anticipo->servicio->valor_conductor)}}</td>

                     @else

                     <td>SIN ASIGNAR</td>
                     <td>NA</td>
                     <td>NA</td>
                     <td>{{$anticipo->cliente->razon_social}}</td>
                     <td>{{$anticipo->conductor->nombres}} {{$anticipo->conductor->apellidos}}</td>
                     <td>${{number_format($anticipo->valor_cliente)}}</td>
                     <td>${{number_format($anticipo->valor)}}</td>

                     @endif
                     <td>${{number_format($anticipo->valor)}}</td>
                     <td>${{number_format($totalAbonos)}}</td>
                     <td>${{number_format($anticipo->valor-$totalAbonos)}}</td>
                     <td>
                      @if($anticipo->estado==2)
                      Pagado
                      @else
                      Activo
                      @endif
                     </td>
                     <td><a href="{{route('anticipos.abonos',['id'=>$anticipo->id])}}">Listar Abonos</a></td>
                     
                     <td>
                        <a class="text-success mr-2" href="{{route('anticipos.edit',['id'=>$anticipo->id])}}" title="Editar">
                          <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                        </a>
                        <a class="text-default mr-2 descargar" href="{{route('anticipos.descargar', $anticipo->id)}}" title="Descargar Anticipo" target="_blank" >
                        <i class="nav-icon  i-File-Horizontal-Text font-weight-bold"></i></i></a>
                        
                        <a class="text-danger mr-2 eliminar" href="{{route('anticipos.delete.post', $anticipo->id)}}" title="Eliminar" >
                        <i class="nav-icon i-Close-Window font-weight-bold"></i></i></a>

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
   				    	<?php echo $anticipos->links(); ?>
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

  $('#conductor').select2({
   theme: 'bootstrap-5',
   //multiple: true

  });
 	$('.eliminar').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-delete-form').attr('action',url);

 		Swal
	    .fire({
	        title: "Eliminar",
	        text: "Está seguro de eliminar este usuario?",
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
