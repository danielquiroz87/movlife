
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Empleados</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>
 @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{{ $message }}</strong>
    </div>
  @endif

  <div class="row">
          <div class="col-md-12">
            <h1>Empleados</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                   
                    <a class="btn btn-primary" href="{{route('employes.new')}}">Nuevo</a>
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Empleados</h3>
              
                  @include('partials.search_table', ['q' => $q])

              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id Empleado</th>
                      <th>Documento</th>
                      <th>Nombres</th>
                      <th>Email</th>
                      <th>Teléfono</th>
                      <th>Celular</th>
                      <th>Whatsapp</th>
                      <th>Cargo</th>
                      <th>Activo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($empleados as $user)
                    <tr>
                      <td>{{$user->id}}</td>
                      <td>{{$user->documento}}</td>
                      <td>{{$user->nombres}} {{$user->apellidos}}</td>
                      <td>{{$user->email_contacto}}</td>
                      <td>{{$user->telefono}}</td>
                      <td>{{$user->celular}}</td>
                      <td>{{$user->whatsapp}}</td>
                      <td>{{$user->cargo}}</td>
                      <td>{{$user->activo}}</td>
                      <td>
                      <a class="text-success mr-2" href="{{route('employes.edit', $user->id)}}" title="Editar">
                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                      </a>
                      <a class="text-danger mr-2 eliminar" href="{{route('employes.delete.get', $user->id)}}" title="Eliminar"><i class="nav-icon i-Close-Window"></i>
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
   				    	<?php echo $empleados->appends(['q' => $q])->links(); ?>
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
	        text: "Está seguro de eliminar este Empleado?",
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
