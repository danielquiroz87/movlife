
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Conductores</li>
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
            <h1>Conductores</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  <a class="btn btn-success" href="{{route('conductores.importar')}}" target="_blank" >Importar</a>&nbsp;&nbsp;
                  <a class="btn btn-primary" href="{{route('conductores.new')}}">Nuevo</a>&nbsp;&nbsp;
                  <a class="btn btn-success" id=# href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['exportar' => true])) }}
                  " target="_blank" >Descargar</a>

            </div>
          </div>
  </div>




  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Conductores</h3>

                <form class="form" method="GET" >
                  <div class="row">
                    <div class="col-md-4 form-group mb-4">  
                        <label>Buscar</label>
                        <input name="q" class="form-control " value="{{$q}}" ></input>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                      <label><strong>Conductor Servicio:</strong></label>
                          <select id="filtros_conductor" name="filtros_conductor[]" multiple="multiple" class="form-control">
                              <?php echo Helper::selectConductores($filtros['conductor']) ?>
                          </select>
                    </div>
                    <div class="col-md-3 form-group mb-3">
                      <br/>
                      <button class="btn btn-success">Buscar</button>
                  </div>
                  </div>

                 
              </form>
         
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
                        @foreach ($conductores as $user)
                        <tr>
                          <td>{{$user->documento}}</td>
                          <td>{{$user->nombres}} {{$user->apellidos}}</td>
                          <td>{{$user->email_contacto}}</td>
                          <td>{{$user->telefono}}</td>
                          <td>{{$user->celular}}</td>
                          <td>{{$user->whatsapp}}</td>
                          <td>{{$user->activo}}</td>
                          <td>
                            <a class="text-success mr-2" href="{{route('conductores.edit', $user->id)}}" title="Editar"> 
                            <i class="nav-icon i-Pen-2 font-weight-bold"></i></a>
                            <a class="text-danger mr-2 eliminar" href="{{route('conductores.delete.get', $user->id)}}" title="Eliminar" class=""><i class="nav-icon i-Close-Window font-weight-bold"></i></a>
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
   				    	<?php echo $conductores->appends(['q' => $q])->links(); ?>
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

  $('#filtros_conductor').select2({
   theme: 'bootstrap-5',
   multiple: true,

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
