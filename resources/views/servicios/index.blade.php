@extends('layouts.master')
@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Servicios</li>
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
            <h1>Servicios</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-success" href="{{route('servicios.new')}}">Nuevo</a>&nbsp;&nbsp;
                    <a class="btn btn-primary" href="{{route('servicios.descargar')}}" target="_blank" >Descargar</a>&nbsp;&nbsp;
                    <a class="btn btn-success" href="{{route('servicios.importar')}}" target="_blank" >Importar</a>&nbsp;&nbsp;
                    <a class="btn btn-primary" id=# href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['exportarplacas' => true])) }}
" target="_blank" >Descargar Informe Placas</a>&nbsp;&nbsp;
            </div>
            
          </div>
  </div>

  <div class="row">


  <div class="col-md-12 mb-4">

  <div class="">
      <livewire:contadores-servicios>
  </div>
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Servicios</h3>
              <!-- /.card-header -->
            <form>

              <div class="row">
                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Id:</strong></label>
                    <input type="text" class="form-control" name="filtros[id]" value="{{$filtros['id']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Lote Id:</strong></label>
                    <input type="text" class="form-control" name="filtros[importadoraux]" value="{{$filtros['importadoraux']}}" >
                  </div>
                  
                 <div class="col-md-2 form-group mb-3">
                    <label><strong>Estado Servicio:</strong></label>
                         <select name="filtros[estado]" class="form-control">
                            <option value="">Todos</option>
                            <option value="0" @if ($filtros['estado']==0 && $filtros['estado']!=""  ) selected="selected" @endif>Pendiente</option>
                            <option value="1" @if ($filtros['estado']==1) selected="selected" @endif>Activo</option>
                            <option value="2" @if ($filtros['estado']==2) selected="selected" @endif>En Proceso</option>
                            <option value="3" @if ($filtros['estado']==3) selected="selected" @endif>Cumplido</option>
                            <option value="4" @if ($filtros['estado']==4) selected="selected" @endif>Cancelado</option>

                         </select>
                  </div>

                  <div class="col-md-2 form-group mb-3">
                    <label><strong>Fecha Solicitud Servicio Inicial:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_inicial]" value="{{$filtros['fecha_inicial']}}" >
                  </div>

                  <div class="col-md-2 form-group mb-3">
                    <label><strong>Fecha Solicitud Servicio Final:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Creación Servicio:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_inicial_creacion]" value="{{$filtros['fecha_inicial_creacion']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Final Creación Servicio:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_final_creacion]" value="{{$filtros['fecha_final_creacion']}}" >
                  </div>

                <div class="col-md-3 form-group mb-3">
                    <label><strong>Pasajero:</strong></label>
                    <select id="filtros_pasajero" name="filtros_pasajero[]" multiple="multiple" class="form-control">
                      <?php echo Helper::selectPasajeros($filtros['pasajero']) ?>
                    </select>
                 </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Clientes: </strong></label>
                         <select id="filtros_cliente" name="filtros_cliente[]" multiple="multiple" class="form-control">
                            <?php echo Helper::selectClientes($filtros['cliente']) ?>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Sedes:</strong></label>
                         <select id="filtros_uri_sedes"  name="filtros_urisede[]" multiple="multiple" class="form-control">
                            <?php echo Helper::selectSedes($filtros['uri_sede']) ?>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Conductor Servicio:</strong></label>
                         <select id="filtros_conductor" name="filtros_conductor[]" multiple="multiple" class="form-control">
                            <?php echo Helper::selectConductores($filtros['conductor']) ?>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Coordinador:</strong></label>
                    <select id="filtros_coordinador" class="form-control" multiple="multiple" name="filtros_coordinador[]">
                        <?php echo Helper::selectEmpleadosDirectores($filtros['coordinador']) ?>
                    </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label>&nbsp;&nbsp;&nbsp;</label><br/>
                    <button class="btn btn-success">Filtrar</button>
                  </div>
              </div>
            </form>

             <table id="hidden_column_table" class="table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Cliente</th>
                      <th>Tpo Servicio</th>
                      <th>Placa</th>
                      <th>Conductor</th>
                      <th>Pasajero</th>
                      <th>Fecha Solicitud</th>
                      <th>Fecha Servicio</th>
                      <th>Hora Recogida</th>
                      <th>Dirección Recogida</th>
                      <th>Dirección Destino</th>
                      <th>Hora Estimada Salida</th>
                      <th>Valor Cliente</th>
                      <th>Valor Conductor</th>
                      <th>Estado</th>
                      <th>Anticipos</th>

                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($servicios as $servicio)
                    <tr>
                    <td>{{$servicio->id}}</td>
                     <td>
                        @if($servicio->cliente)
                           {{$servicio->cliente->documento}},{{$servicio->cliente->razon_social}} 
                        @else
                          N/A
                        @endif
                      </td>
                     <td>{{$servicio->tipoServicio->nombre}}</td>
                     <td>{{$servicio->placa}}</td>
                     <td>{{$servicio->conductor->documento}},{{$servicio->conductor->nombres}}</td>
                     <td>
                        @if($servicio->pasajero)
                          {{$servicio->pasajero->nombres}} {{$servicio->pasajero->apellidos}}
                        @else
                         N/A
                        @endif
                        
                      </td>
                     <td>{{$servicio->fecha_solicitud}}</td>
                     <td>{{$servicio->fecha_servicio}}</td>
                     <td>{{$servicio->hora_recogida}}</td>
                     <td  style="max-width: 100px">{{$servicio->origen}}</td>
                     <td  style="max-width: 100px">{{$servicio->destino}}</td>
                     <td>{{$servicio->hora_estimada_salida}}</td>
                     <td>{{number_format($servicio->valor_cliente)}}</td>
                     <td>{{number_format($servicio->valor_conductor)}}</td>
                     <td>
                      @if($servicio->estado==0)
                        Pendiente
                      @elseif($servicio->estado==1)
                        Activo
                      @elseif($servicio->estado==2)
                        Proceso
                       @elseif($servicio->estado==3)
                        Cumplido
                       @endif
                       @if($servicio->estado==4)
                       Cancelado
                       @endif

                     </td>
                    <td style="text-align: center;">
                      <a class="text-default mr-2 modalanticipos" href="{{route('anticipos.fromservicio',['id'=>$servicio->id])}}"  data-valor="{{$servicio->valor_conductor}}"   > 
                     <i class="nav-icon i-Money-2 font-weight-bold"></i>
                    </a>
                    </td>
                     <td>
                        <a class="text-success mr-2 coordinador" href="{{route('servicios.edit',['id'=>$servicio->id])}}" title="Editar">
                       <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                        </a>
                      
                        <a class="text-default mr-2 duplicar" href="{{route('servicios.edit', $servicio->id)}}" title="Duplicar Servicio" >
                          <i class="nav-icon i-Data-Copy font-weight-bold"></i></i></a>
                        <a class="text-danger mr-2 eliminar" href="{{route('servicios.delete', $servicio->id)}}" title="Eliminar" >
                          <i class="nav-icon i-Close-Window font-weight-bold"></i></i>
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
   				    	<?php echo $servicios->withQueryString()->links(); ?>
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



          <div class="modal fade" id="modalAnticipos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Nuevo Anticipo Servicio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                 <form action="#" method="GET" id="new-anticipo" enctype="multipart/form-data"  >
                                      {{ csrf_field() }}
                                      <input type="hidden" name="id" id="id" value="0">
                                       <div class="col-md-10 form-group mb-3">
                                        <label><strong> Tipo Anticipo:</strong></label>
                                        <select name="tipo_anticipo" class="form-control" id="tipo_anticipo">
                                          <option value="1">Total</option>
                                          <option value="2">Parcial</option>

                                        </select>
                                      </div>
                                      <div class="col-md-10 form-group mb-3">
                                        <label><strong>Valor Servicio Conductor:</strong></label>
                                          <input type="text" name="valorconductor" placeholder="20000" id="valorconductor" class="form-control" required>
                                      </div>
                                      <div class="col-md-10 form-group mb-3">
                                        <label><strong>Valor Anticipo:</strong></label>
                                          <input type="text" name="valor" id="valoranticipo" placeholder="20000" class="form-control" required>
                                      </div>
                                     
                                  </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary enviar" id="enviaranticipo">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>


@endsection

@section('bottom-js')
<script type="text/javascript">
 $(document).ready(function(){

  $('.modalanticipos').click(function(e){
    e.preventDefault();
    var url=$(this).attr('href');
    valor=$(this).data('valor');
    $('#new-anticipo').attr('action',url);
    $('#valoranticipo').val(valor);
    $('#valorconductor').val(valor);


    $("#modalAnticipos").modal('show');
  })

  $('#enviaranticipo').click(function(e){
    e.preventDefault();
    $('#new-anticipo').submit();
  })

  $('#filtros_cliente').select2({
   theme: 'bootstrap-5',
   multiple: true,

  });

  $('#filtros_pasajero').select2({
   theme: 'bootstrap-5',
   multiple: true
  });

  $('#filtros_conductor').select2({
   theme: 'bootstrap-5',
   multiple: true,

  });

  $('#filtros_uri_sedes').select2({
   theme: 'bootstrap-5',
   multiple: true

  });

  $('#list-uri_sedes').change(function(){
   $('#uri_sedes').val($(this).val());
  })

  $('#filtros_coordinador').select2({
   theme: 'bootstrap-5'
  });



 	$('.eliminar').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-delete-form').attr('action',url);

 		Swal
	    .fire({
	        title: "Eliminar",
	        text: "Está seguro de eliminar este registro?",
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
