
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Vehiculos</li>
          <li>Mantenimientos</li>

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
            <h1>Mantenimiento Vehiculos</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                <span class="m-auto"></span>
                <a class="btn btn-primary" href="{{route('vehiculos.mantenimientos.new')}}">Nuevo</a>&nbsp;&nbsp;
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Mantenimientos</h3>
              @include('partials.search_table', ['q' => $q])
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Placa</th>
                      <th>Item</th>
                      <th>Intervalo Km</th>
                      <th>Kilometro Actual</th>
                      <th>Fecha Último Cambio</th>
                      <th>Última Revisión Kilometraje</th>
                      <th>Proveedor</th>
                      <th>Observaciones</th>
                      <th>Valor</th>
                      <th>Km Restantes</th>
                      <th>Siguiente Revisión</th>
                      <th>Acciones</th>

                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($mantenimientos as $ma)
                    <tr>
                      <td>{{$ma->detalle_id}}</td>
                      <td>{{$ma->placa}}</td>
                      <td> {{$ma->nombre}}</td>
                      <td> {{number_format($ma->intervalo_km)}}</td>
                      <td>{{number_format($ma->kilometros)}}</td>
                      <td>{{$ma->created_at}}</td>
                      <td>{{number_format($ma->km_ultima_revision)}}</td>
                      <td>{{$ma->proveedor}}</td>
                      <td>{{$ma->observaciones}}</td>
                      <td>{{number_format($ma->valor)}}</td>
                      <td>{{number_format($ma->km_restantes)}}</td>
                      <td>{{number_format($ma->kilometros+$ma->intervalo_km)}}</td>

                      <td>
                        <a class="text-success mr-2 coordinador" href="{{route('vehiculos.mantenimientos.edit',['id'=>$ma->detalle_id])}}" title="Editar">
                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
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
   				    	<?php echo $mantenimientos->appends(['q' => $q])->links(); ?>
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


        	 <form action="#" method="GET" id="user-delete-form"  >
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
	        text: "Está seguro de eliminar este Pasajero?",
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
