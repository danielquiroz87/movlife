
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
            <h1>Servicios Web</h1>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Servicios</h3>
              <!-- /.card-header -->

             <table id="hidden_column_table" class="table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Pasajero</th>
                      <th>Placa</th>
                      <th>Conductor</th>
                      <th>Fecha Solicitud</th>
                      <th>Fecha Servicio</th>
                      <th>Hora Recogida</th>
                      <th>Hora Regreso</th>
                      <th>Dirección Recogida</th>
                      <th>Dirección Destino</th>
                      <th>Uri / Sede</th>
                      <th>Tipo Servicio</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($servicios as $servicio)
                    <tr>
                    
                      <td>{{$servicio->id}}</td>
                      <td style="max-width: 120px">
                        @if($servicio->pasajero)
                           {{$servicio->pasajero->documento}},<br/>{{$servicio->pasajero->nombres}} {{$servicio->pasajero->apellidos}}
                           
                        @else
                         NA
                        @endif
                      </td>
                     <td>{{$servicio->vehiculo->placa}}</td>
                     <td>{{$servicio->conductorServicio->nombres}} {{$servicio->conductorServicio->apellidos}}</td>
                    <td>{{date('d/m/y',strtotime($servicio->fecha_solicitud))}}</td>
                     <td>{{date('d/m/y',strtotime($servicio->fecha_servicio))}}</td>
                     <td>{{date('H:i',strtotime($servicio->hora_recogida))}}</td>
                     <td> {{date('H:i',strtotime($servicio->hora_regreso))}}</td>
                     <td >{{$servicio->origen}}</td>
                     <td >{{$servicio->destino}}</td>
                     <td >
                      @if($servicio->uri_sede)
                        {{$servicio->sede->nombre}}
                      @else
                      N/A
                      @endif
                    </td>
                    <td>{{$servicio->tiposervicio->nombre}}</td>
                    <td>
                       @if($servicio->estado==0)
                        Pendiente
                       @elseif($servicio->estado==1)
                        Activo
                       @elseif($servicio->estado==3)
                        Cumplido
                       @endif

                       @if($servicio->estado==4)
                       Cancelado
                       @endif

                     </td>
                     <td>
                        @if($servicio->estado==1)
                        <br/>
                        <a class="btn btn-default recoger" href="{{route('web.conductor.recogerpasajero', $servicio->id)}}" title="Recoger Pasajero" >
                      Recoger Pasajero  
                      </a>
                 
                        <a class="btn btn-success finalizar" href="{{route('web.conductor.finalizarservicio', $servicio->id)}}" title="Finalizar Servicio" >
                         Finalizar Servicio</a>
          

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
   				    	@if($servicios)
                    <?php echo $servicios->withQueryString()->links(); ?>
                @endif
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>



          <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Finalizar Servicio</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                                 <form action="#" method="POST" id="user-finalizar-form" enctype="multipart/form-data"  >
                                      {{ csrf_field() }}
                                      <input type="hidden" name="id" id="id" value="0">
                                       <div class="col-md-10 form-group mb-3">
                                        <label><strong>Hora Finalización:</strong></label>
                                        <input type="time" name="hora_final" id="hora_final" value="" class="form-control" required />
                                      </div>
                                      <div class="col-md-10 form-group mb-3">
                                        <label><strong>Imagen:</strong></label>
                                          <input type="file" name="file" id="file" class="form-control" required>
                                      </div>
                                      <input type="hidden" name="documento" value="{{Request::get('documento')}}" >
                                  </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary enviar">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>



      


@endsection

@section('bottom-js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>

<script type="text/javascript">

var form = $("#user-finalizar-form");
$.validator.messages.required = 'Este campo es requerido';

$('#user-finalizar-form').validate({
  rules: {
        hora_final: { required:true },
        file:{ required:true },
        
  },messages: {
                
  },
    
});



 $(document).ready(function(){
 	$('.finalizar').click(function(e){
 		e.preventDefault();
 		var url=$(this).attr('href');
 		$('#user-finalizar-form').attr('action',url);
    $('#exampleModal').modal();

 	})


  $('.enviar').click(function(e){
    e.preventDefault();
    valid=$('#user-finalizar-form').validate();
    if(valid){
        $('#user-finalizar-form').submit();
    }
    
  })
 })
</script>
@endsection
