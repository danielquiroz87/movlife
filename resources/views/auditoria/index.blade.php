
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Auditoria</li>
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
            <h1>Auditoria</h1>
           
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Auditoria</h3>
              @include('partials.search_table', ['q' => $q])
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha Creación</th>
                      <th>Fecha Actualización</th>
                      <th>Evento</th>
                      <th>Usuario</th>
                      <th>Modulo</th>
                      <th>Id Registro</th>
                      <th>Valor Anterior</th>
                      <th>Nuevo Valor</th>
                      <th>Ip</th>
                      <th>User Agente</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($auditoria as $au)
                    <tr>
                      <td>{{$au->id}}</td>
                      <td>{{$au->created_at}}</td>
                      <td>{{$au->updated_at}}</td>
                      <td>{{$au->event}}</td>
                      <td>{{$au->usuario->name}} / {{$au->usuario->email}}</td>
                      <td>
                        {{$au->getModulo()}}
                      </td>
                      <td>{{$au->auditable_id}}</td>
                      <td style="max-width: 200px" >{{$au->old_values}}</td>
                      <td style="max-width: 200px" >{{$au->new_values}}</td>
                      <td>{{$au->ip_address}}</td>
                      <td>{{$au->user_agent}}</td>

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
   				    	<?php echo $auditoria->appends(['q' => $q])->links(); ?>
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
