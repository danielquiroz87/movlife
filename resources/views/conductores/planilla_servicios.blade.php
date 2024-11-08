@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Planilla Servicios</li>
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
            <h1>Planilla Servicios</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  
                    <a class="btn btn-primary nueva" href="#">Nuevo</a>
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Planillas</h3>
              @include('partials.search_table', ['q' => $q])
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Cliente Id</th>
                      <th>File</th>
                      <th>Aprobada</th>
                      <th>Observaciones</th>
                      <th>Usuario</th>
                      <th>Conductor</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($planillas as $planilla)
                    <tr>
                      <td>{{$planilla->id}}</td>
                      <td>{{$planilla->fecha}}</td>
                      <td>{{$planilla->cliente_id}}</td>
                      <td>{{$planilla->file}}</td>
                      <td>{{$planilla->aprobada}}</td>
                      <td>{{$planilla->observacion}}</td>
                      <td>{{$planilla->user_id}}</td>
                      <td>{{$planilla->conductor_id}}</td>

                      <td>
                        <a class="text-success mr-2" href="{{route('tarifario.edit', $planilla->id)}}" title="Editar"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a>
                      	<a class="text-danger mr-2 eliminar" href="{{route('tarifario.delete.get', $planilla->id)}}" title="Eliminar" ><i class="nav-icon i-Close-Window"></i></a>
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
   				    	<?php echo $planillas->appends(['q' => $q])->links(); ?>
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>


        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Nueva Planilla</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                 <form action="{{route('planillaservicios.save')}}" method="POST" id="form-planilla" enctype="multipart/form-data"  >
                                      {{ csrf_field() }}
                                      <input type="hidden" name="id" id="id" value="0">
                                      <div class="col-md-12 form-group mb-3">
                                        <label><strong>Fecha Solicitud:</strong></label>
                                        <input type="date" name="fecha" value="{{date('Y-m-d')}}"  class="form-control" placeholder="" maxlength="20" required>
                                      </div>
                                      <div class="col-md-12 form-group mb-3">
                                      <select name="id_cliente" id="id_cliente" class="form-control">
                                        <?php echo Helper::selectClientes() ?>
                                      </select>
                                      </div>
                                      <div class="col-md-12 form-group mb-3">
                                      <div class="col-md-10 form-group mb-3">
                                        <label><strong>Archivo Planilla:</strong></label>
                                          <input type="file" name="file" id="file" class="form-control" required>
                                      </div>
                                      </div>
                                    
                                     
                                  </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary enviar" id="enviarplanilla">Enviar</button>
                        </div>
                    </div>
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
  $('#id_cliente').select2({
   theme: 'bootstrap-5'
 });
  $('.nueva').click(function(e){
    e.preventDefault();
    $('#modal').modal('show');
  })

  $('#enviarplanilla').click(function(e){
    e.preventDefault();
    $('#form-planilla').submit();
  })

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
