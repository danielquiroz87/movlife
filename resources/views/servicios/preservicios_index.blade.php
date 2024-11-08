
@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li>Pre Servicios</li>
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
            <h1>Pre Servicios Web</h1>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Pre Servicios</h3>

            <form>
              <div class="row">

                <div class="col-md-3 form-group mb-2">
                    <label><strong>Id Preservicio:</strong></label>
                         <input type="text" class="form-control" name="filtros[id]" value="{{$filtros['id']}}" >
                  </div>

                  <div class="col-md-2 form-group mb-2">
                    <label><strong>Fecha Inicial:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_inicial]" value="{{$filtros['fecha_inicial']}}" >
                  </div>

                  <div class="col-md-2 form-group mb-2">
                    <label><strong>Fecha Final:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
                  </div>
                  
                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Pasajero:</strong></label>
                    <select id="filtros_pasajero" name="filtros_pasajero[]" multiple="multiple" class="form-control">
                      <?php echo Helper::selectPasajeros($filtros['pasajero']) ?>
                    </select>
                 </div>

                <div class="col-md-3 form-group mb-3">
                  <label><strong>Coordinador(a):</strong></label>
                  <select class="form-control" name="filtros_coordinador[]" multiple="multiple"  id="filtros_coordinador" >
                  <?php echo Helper::selectEmpleadosDirectores($filtros['coordinador']) ?>
                  </select>
                </div>
                <div class="col-md-2 form-group mb-3">
                    <label><strong>Sedes:</strong></label>
                         <select id='filtros_uri_sede' name="filtros_urisede[]" multiple="multiple"  class="form-control">
                            <?php echo Helper::selectSedes($filtros['uri_sede']) ?>
                         </select>
                  </div>
                <div class="col-md-2 form-group mb-3">
                    <label><strong>Estado:</strong></label>
                    <select name="filtros[estado]" class="form-control">
                          <option value="">Todos</option>
                           <option value="1" @if ( $filtros['estado']==1) selected='selected' @endif >No Asignado</option>
                           <option value="2" @if ( $filtros['estado']==2) selected='selected' @endif >Asignado</option>
                    </select>
                  </div>

               
                  <div class="col-md-3 form-group mb-3">
                    <label>&nbsp;&nbsp;&nbsp;</label><br/>
                    <button class="btn btn-success">Filtrar</button>
                  </div>
              </div>
            </form>

             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id Preservicio</th>
                      <th>Pasajero</th>
                      <th>Fecha Solicitud</th>
                      <th>Fecha Servicio</th>
                      <th>Hora Recogida</th>
                      <th>Hora Regreso</th>
                      <th>Dirección Recogida</th>
                      <th>Dirección Destino</th>
                      <th>Uri / Sede</th>
                      <th>Tipo Servicio</th>
                      <th>Tipo Viaje</th>
                      <th>Asignado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($servicios as $servicio)
                    <tr>
                    
                      <td>{{$servicio->id}}</td>
                      <td style="max-width: 120px">
                        @if($servicio->pasajero)
                           {{$servicio->pasajero->documento}},<br/>{{$servicio->pasajero->nombres}} {{$servicio->pasajero_apellidos}}
                           {{$servicio->pasajero->email_contacto}},<br/>{{$servicio->pasajero->celular}}
                        @else
                          {{$servicio->pasajero_documento}},<br/>{{$servicio->pasajero_nombres}} {{$servicio->pasajero_apellidos}}<br/>
                          {{$servicio->pasajero_email}},{{$servicio->pasajero_celular}}
                        @endif
                      </td>
                      <td>
                      {{date('d/m/y H:i:s',strtotime($servicio->fecha_solicitud))}}
                     </td>
                     <td>
                      {{date('d/m/y',strtotime($servicio->fecha_servicio))}}
                     </td>
                     <td>{{$servicio->hora_recogida}}</td>
                     <td>{{$servicio->hora_regreso}}</td>
                     <td >{{$servicio->origen}}</td>
                     <td >{{$servicio->destino}}</td>
                     <td  >
                      @if($servicio->uri_sede)
                        {{$servicio->sede->nombre}}
                      @else
                      N/A
                      @endif
                    </td>
                    <td>{{$servicio->tiposervicio->nombre}}</td>
                    <td>{{$servicio->tipoViaje()}}</td>
                    <td>
                      @if($servicio->estado==1)
                        NO 
                      @else
                        SI
                      @endif
                    </td>
                     <td>
                     @if($servicio->estado==1)
                        @if(session::get('is_driver')==true || auth()->user()->superadmin==1  )
                          @if($servicio->placa=="")
                          <a class="text-default mr-2 asignar-conductor" href="#" data-id="{{$servicio->id}}"  @if($servicio->pasajero) data-sede="{{$servicio->pasajero->uri_sede}}"  data-cliente="{{$servicio->pasajero->cliente_id}}"  @else  data-cliente="" data-sede="{{$servicio->uri_sede}}" @endif  title="Asignar Conductor" >
                          <i class="nav-icon i-Car-Wheel font-weight-bold"></i></i></a>
                          @endif

                          @if(auth()->user()->superadmin==1  )
                          <a class="text-default mr-2 crear servicio" href="{{route('servicios.from.preservicio', $servicio->id)}}" title="Crear Servicio" >
                          <i class="nav-icon i-Data-Copy font-weight-bold"></i></i></a>

                          <a class="text-danger mr-2 eliminar" href="{{route('preservicios.delete', $servicio->id)}}" title="Eliminar" >
                          <i class="nav-icon i-Close-Window font-weight-bold"></i></i>
                          @endif

                        @else 

                        <a class="text-default mr-2 asignar-conductor" href="#" data-id="{{$servicio->id}}" title="Asignar Conductor" >
                          <i class="nav-icon i-Car-Wheel font-weight-bold"></i></i></a>
                          
                        <a class="text-default mr-2 crear servicio" href="{{route('servicios.from.preservicio', $servicio->id)}}" title="Crear Servicio" >
                        <i class="nav-icon i-Data-Copy font-weight-bold"></i></i></a>

                        @endif

                        
                     @endif
                       
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


          <div class="modal fade" id="modalPreservicio" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <form action="{{route('preservicios.placa.save')}}" method="POST" id="form-preservicio" enctype="multipart/form-data"  >
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Asignar Conductor Preservicio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                      {{ csrf_field() }}
                                      <input type="hidden" name="id" id="preservicioid" value="0">
                                      <div class="col-md-12 form-group mb-3">
                                        <label><strong>Cliente:</strong></label>
                                            <select name="id_cliente" id="id_cliente" class="form-control">
                                                <?php echo Helper::selectClientes() ?>
                                            </select>
                                      </div>

                                      <div class="col-md-12 form-group mb-3">
                                        <label><strong>Sede:</strong></label>
                                            <select name="uri_sede" id="uri_sede" class="form-control">
                                                <?php echo Helper::selectSedes() ?>
                                            </select>
                                      </div>

                                      <div class="col-md-12 form-group mb-3">
                                        <label><strong>Placa (Vehículo):</strong></label>
                                        <input type="text" name="placa" id="placa" value="" class="form-control" maxlength="6"
                                        onkeyup="javascript:this.value=this.value.toUpperCase();"
                                        />
                                      </div>

                                      <div class="col-md-12 form-group mb-3">
                                        <label><strong>Conductor (Pago):</strong></label>
                                            <select name="id_conductor_pago" id="conductor_pago" class="form-control">
                                                <?php echo Helper::selectConductores() ?>
                                            </select>
                                      </div>

                                      <div class="col-md-12 form-group mb-3">
                                        <label><strong>Conductor Prestador Servicio:</strong></label>
                                            <select name="id_conductor_servicio" id="conductor_servicio" class="form-control">
                                                <?php echo Helper::selectConductores() ?>
                                            </select>
                                      </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary enviar" id="enviarplanilla">Enviar</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>


@endsection

@section('bottom-js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>

<script type="text/javascript">
 $(document).ready(function(){


  var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#form-preservicio').validate({
  rules: {
        id_cliente: { required:true },
        uri_sede: { required:true },
        placa:{ required:true },
        id_conductor_pago:{ required:true },
        id_conductor_servicio:{ required:true }
        
    },messages: {
                
            },
    
})

$("#submit").validate({ 
 onsubmit: false,
  
 submitHandler: function(form) {  
   if ($(form).valid())
   {
       form.submit(); 
   }
   return false; // prevent normal form posting
 }
});

  $('.asignar-conductor').click(function(e){
    e.preventDefault();
    var id=$(this).data('id');
    var sede=$(this).data('sede');
    var cliente=$(this).data('cliente');

    $('#preservicioid').val(id);
    $('#uri_sede').val(sede);
    $('#id_cliente').val(cliente);


    $("#modalPreservicio").modal('show');
  });

  $("#placa").blur(function(){
    var placa=$(this).val();
    $("#conductor_pago").html("");
    $("#conductor_servicio").html("");


    $.get('/conductores/placa/'+placa,function(html){
      $("#conductor_pago").html(html);
      $("#conductor_servicio").html(html);
    })
  })

  $('#filtros_uri_sede').select2({
   theme: 'bootstrap-5',
   multiple:true
 });
 $('#filtros_coordinador').select2({
   theme: 'bootstrap-5',
   multiple:true
 });

 $('#list-uri_sedes').change(function(){
   $('#uri_sedes').val($(this).val());
 })
 
 $('#filtros_pasajero').select2({
   theme: 'bootstrap-5',
   multiple:true
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
