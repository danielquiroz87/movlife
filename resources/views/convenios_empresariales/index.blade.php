
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Convenios Empresariales</li>
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

  @if ($message = Session::get('flash_alert_message'))
    <div class="alert alert-warning alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button> 
            <strong>{!! nl2br($message)!!}</strong>
    </div>
  @endif

  <div class="row">
          <div class="col-md-12">
            <h1>Convenios Empresariales</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                  <a class="btn btn-primary" href="{{route('convenios.new')}}">Nuevo</a>
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Convenios</h3>

              <form>

              <div class="row">
                  

              
                  <div class="col-md-2 form-group mb-3">
                    <label><strong>Fecha Inicial:</strong></label>
                        <input type="date" class="form-control" name="filtros[fecha_inicial]" value="{{$filtros['fecha_inicial']}}" >
                  </div>

                  <div class="col-md-2 form-group mb-3">
                    <label><strong>Fecha Final:</strong></label>
                        <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
                  </div>
                
                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Empresa: </strong></label>
                        <select id="filtros_empresa" name="filtros[empresa]"  class="form-control">
                            <?php echo Helper::selectEmpresas($filtros['empresa']) ?>
                        </select>
                  </div>
                  
                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Conductor:</strong></label>
                        <select id="filtros_conductor" name="filtros[conductor]"  class="form-control">
                            <?php echo Helper::selectConductores($filtros['conductor']) ?>
                        </select>
                  </div>

                  <div class="col-md-2 form-group mb-3">
                    <label><strong>Placa:</strong></label>
                    <input type="text" class="form-control" name="filtros[placa]" value="{{$filtros['placa']}}" >

                  </div>


                  <div class="col-md-3 form-group mb-3">
                    <button class="btn btn-success">Filtrar</button>
                  </div>
              </div>
              </form>

              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha Inicial</th>
                      <th>Fecha Final</th>
                      <th>Nro Resolución</th>
                      <th>Empresa</th>
                      <th>Representante Legal</th>
                      <th>Conductor</th>
                      <th>Placa</th>
                      <th>Convenio Firmado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($convenios as $convenio)
                    <tr>
                      <td>{{$convenio->id}}</td>
                      <td>{{$convenio->fecha_inicial}}</td>
                      <td>{{$convenio->fecha_final}}</td>
                      <td>{{$convenio->numero_resolucion}}</td>
                      <td>{{$convenio->empresa->razon_social}}</td>
                      <td>{{$convenio->empresa->representante_legal_documento}},
                      {{$convenio->empresa->representante_legal_nombres}}
                      </td>
                      <td>{{$convenio->conductor->nombres}} {{$convenio->conductor->apellidos}}</td>
                      <td>{{$convenio->placa}}</td>
                      <td><a href="{{asset($convenio->convenio_firmado)}}" target="_blank">Descargar Convenio</a></td>

                    
                      <td>
                         <a class="text-success mr-2" href="{{route('convenios.edit', $convenio->id)}}" title="Editar">
                            <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                        </a>
                         <a class="text-default mr-2 convenio" href="{{route('convenios.descargar', $convenio->id)}}" title="Descargar Convenio" target="_blank" >
                            <i class="nav-icon  i-File-Horizontal-Text font-weight-bold"></i></i></a>
                      	 <a class="text-danger mr-2 eliminar" href="{{route('convenios.delete.get', $convenio->id)}}" title="Eliminar" >
                            <i class="nav-icon i-Close-Window"></i>
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
   				    	<?php echo $convenios->appends(['q' => $q])->links(); ?>
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

  $('#filtros_empresa').select2({
   theme: 'bootstrap-5',

  });
  $('#filtros_conductor').select2({
   theme: 'bootstrap-5',

  });

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
