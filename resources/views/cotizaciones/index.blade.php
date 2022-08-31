
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Cotizaciones</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>


  <div class="row">
          <div class="col-md-12">
            <h1>Cotizaciones</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-primary" href="{{route('cotizaciones.new')}}">Nuevo</a>
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Cotizaciones</h3>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
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
                      <th>Descargar Ficha</th>
                      <th colspan="3">Acciones</th>
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
                     <td> <a class="text-success mr-2 pdf" href="{{route('cotizaciones.descargar',['id'=>$cotizacion->id])}}" target="_blank" title="Descargar Ficha"><i class="nav-icon i-pdf font-weight-bold"></i>Descargar
                      </a>
                     </td>
                     <td>
                      <a class="text-success mr-2" href="{{route('cotizaciones.edit',['id'=>$cotizacion->id])}}" title="Editar">
                          <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                      </a>
                      <a class="text-danger mr-2 eliminar" href="{{route('cotizaciones.delete', $cotizacion->id)}}" title="Eliminar"><i class="nav-icon i-Close-Window font-weight-bold"></i>
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
   				    	<?php echo $cotizaciones->links(); ?>
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
	        text: "Está seguro de eliminar esta cotización?",
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
	            console.log("*No se elimina la venta*");
	        }
	    });



 	})
 })
</script>
@endsection
