
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Vehiculos</li>
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
            <h1>Vehiculos</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                @if(auth()->user()->superadmin==1 )
                  <span class="m-auto"></span>
                   <a class="btn btn-primary" href="{{route('vehiculos.new')}}">Nuevo</a>
                   &nbsp;&nbsp;
                   <a class="btn btn-success" href="{{route('vehiculos.descargar.excel')}}" target="_blank" >Descargar</a>
                   &nbsp;&nbsp;
                   <a class="btn btn-success" href="{{route('vehiculos.importar')}}" target="_blank" >Importar</a>
                   
                @endif
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Vehiculos</h3>


              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Placa</th>
                      <th>Codigo Interno</th>
                      <th>Clase</th>
                      <th>Marca</th>
                      <th>Activo</th>
                      <th>Control Jornada</th>
                      <th>Estado Documentos</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($vehiculos as $vehiculo)
                    <tr>
                      <td>{{$vehiculo->placa}}</td>
                      <td>{{$vehiculo->codigo_interno}}</td>
                      <td>{{$vehiculo->clase->nombre}}</td>
                      <td>{{$vehiculo->marca->nombre}} {{$vehiculo->linea}}</td>
                      <td>{{$vehiculo->activo}}</td>
                     <td> 
                       <a href="{{route('conductores.jornada.placa', ['placa'=>$vehiculo->placa])}}" title="Control Jornada">Control Jornada</a>
                      </td>
                      <td>ok</td>
                      <td>

                      @if(session::get('is_employe')==true || auth()->user()->superadmin==1  )
                      	<a href="{{route('vehiculos.edit', $vehiculo->id)}}" title="Editar"> <i class="nav-icon i-Pen-2 font-weight-bold"></i></a>
                      @endif
                      @if(session::get('is_driver')==true)
                        <a href="{{route('alistamiento.new', $vehiculo->id)}}" title="Alistamiento"> <i class="nav-icon i-Car-Wheel font-weight-bold"></i></a>

                      @endif
                      	<a href="{{route('vehiculos.delete.get', $vehiculo->id)}}" title="Eliminar" class="eliminar"><i class="nav-icon i-Close-Window font-weight-bold"></i></a>
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
   				    
                <?php echo $vehiculos->appends(['q' => $q])->links(); ?>
               
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
	        text: "Está seguro de eliminar este vehiculo?",
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
