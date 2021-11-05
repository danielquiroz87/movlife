
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Home</a></li>
          <li>Usuarios</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>
</div>

  <div class="row">
          <div class="col-md-12">
            <h1>Usuarios</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-primary" href="{{route('user.new')}}">Nuevo</a>
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Usuarios</h3>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Documento</th>
                      <th>Nombres</th>
                      <th>Email Contacto</th>
                      <th>Teléfono</th>
                      <th>Celular</th>
                      <th>Whatsap</th>
                      <th>Activo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($usuarios as $user)
                    <tr>
                      <td>{{$user->documento}}</td>
                      <td>{{$user->nombres}} {{$user->apellidos}}</td>
                      <td>{{$user->email_contacto}}</td>
                      <td>{{$user->telefono}}</td>
                      <td>{{$user->celular}}</td>
                      <td>{{$user->whatsapp}}</td>
                      <td>{{$user->activo}}</td>
                      <td>
                      	<a href="{{route('user.edit', $user->id)}}" title="Editar"><i class="fa fa-fw fa-edit"></i></a>
                      	<a href="{{route('user.delete', $user->id)}}" title="Eliminar" class="eliminar"><i class="fa fa-fw fa-trash"></i></a>
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
   				    	<?php echo $usuarios->links(); ?>
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

@push('page_scripts')
<script type="text/javascript">
 $(document).ready(function(){
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
@endpush
