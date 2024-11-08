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
                  
                    <a class="btn btn-primary" href="{{route('planillaservicios.new')}}">Nuevo</a>
            </div>
          </div>
  </div>


  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body"> 

              <h3 class="card-title mb3">Lista Planillas</h3>
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

                        <div class="col-md-3 form-group mb-3">
                            <label><strong>Cliente:</strong></label>
                                <select id='cliente' name="filtros[cliente]" class="form-control select2">
                                    <?php echo Helper::selectClientes($filtros['cliente']) ?>
                                </select>
                        </div>
                

                        <div class="col-md- form-group mb-3">
                            <label><strong>Sedes:</strong></label>
                                <select id='uri_sedes' name="filtros[uri_sede]" class="form-control select2">
                                    <?php echo Helper::selectSedes($filtros['uri_sede']) ?>
                                </select>
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
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Cliente</th>
                      <th>Sede</th>
                      <th>Conductor</th>
                      <th>Planilla</th>
                      <th>Aprobada</th>
                      <th>Observaciones</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($planillas as $planilla)
                    <tr>
                      <td>{{$planilla->id}}</td>
                      <td>{{$planilla->fecha}}</td>
                      <td>{{$planilla->cliente->razon_social}} </td>
                      <td>{{$planilla->sede->nombre}}</td>
                      <td>{{$planilla->conductor->nombres}} {{$planilla->conductor->apellidos}}</td>
                      <td><a href="{{asset($planilla->file)}}" target="_blank">Planilla</a></td>
                      <td>
                        @if($planilla->aprobada==0)
                            No
                        @else
                            Si
                        @endif
                      </td>
                      <td>{{$planilla->observaciones}}</td>
                      <td>
                        <a class="text-success mr-2" href="{{route('planillaservicios.edit', $planilla->id)}}" title="Editar"><i class="nav-icon i-Pen-2 font-weight-bold"></i></a>
                      	<a class="text-danger mr-2 eliminar" href="{{route('planillaservicios.delete', $planilla->id)}}" title="Eliminar" ><i class="nav-icon i-Close-Window"></i></a>
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





        	 <form action="#" method="GET" id="user-delete-form"  >
    				{{ csrf_field() }}
      			<input type="hidden" name="id" id="userid" value="0">
   
        	</form>

@endsection

@section('bottom-js')
<script type="text/javascript">
 $(document).ready(function(){
  $('.select2').select2({
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
