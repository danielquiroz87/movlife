@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Tarifario</li>
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
            <h1>Tarifario</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  
                    <a class="btn btn-primary" href="{{route('tarifario.new')}}">Nuevo</a>
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Tarifario</h3>
              @include('partials.search_table', ['q' => $q])
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Origen</th>
                      <th>Destino</th>
                      <th>Tipo Vehiculo</th>
                      <th>Tiempo</th>
                      <th>Kilometros</th>
                      <th>Valor Conductor</th>
                      <th>Valor Cliente</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($tarifario as $tarifa)
                    <tr>
                      <td>{{$tarifa->id}}</td>
                      <td>{{$tarifa->origen}}</td>
                      <td>{{$tarifa->destino}}</td>
                      <td>{{$tarifa->tipo_vehiculo}}</td>
                      <td>{{$tarifa->tiempo}}</td>
                      <td>{{$tarifa->kilometros}}</td>
                      <td>{{$tarifa->valor_conductor}}</td>
                      <td>{{$tarifa->valor_cliente}}</td>

                      <td>
                      	<a class="text-success mr-2" href="{{route('tarifario.edit', $tarifa->id)}}" title="Editar"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a>
                      	<a class="text-danger mr-2 eliminar" href="{{route('tarifario.delete.get', $tarifa->id)}}" title="Eliminar" ><i class="nav-icon i-Close-Window"></i></a>
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
   				    	<?php echo $tarifario->appends(['q' => $q])->links(); ?>
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
	        text: "Está seguro de eliminar este Registro?",
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
