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
            <h1>Vista Previa Lote {{$auxid}}</h1>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Lista Previa De Servicios</h3>
              <!-- /.card-header -->
              <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-primary" href="{{route('servicios.descargar',['importadoraux'=>$auxid,'temporal'=>true])}}" target="_blank" >Descargar Lote</a>&nbsp;&nbsp;
                    <a class="btn btn-success" href="{{route('servicios.importador.enviarlote',['auxid'=>$auxid])}}"  >Enviar Lote</a>&nbsp;&nbsp;
                    <a class="btn btn-primary" href="{{route('servicios.importar')}}"> Importar Lote</a>&nbsp;&nbsp;
                    <a class="btn btn-danger" href="{{route('servicios.importador.eliminarlote',['auxid'=>$auxid])}}">Eliminar Lote</a>

            </div>

             <table id="hidden_column_table" class="table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
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
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($servicios as $servicio)
                    <tr>
                    
                      <td>{{$servicio->id}}</td>
                      <td>
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
                       @if($servicio->estado==1 || $servicio->estado==0)
                        Activo
                       @elseif($servicio->estado==3)
                        Cumplido
                       @endif
                       @if($servicio->estado==4)
                       Cancelado
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


        	 <form action="#" method="POST" id="user-delete-form"  >
    				{{ csrf_field() }}
      			<input type="hidden" name="id" id="userid" value="0">
   
        	</form>


@endsection

@section('bottom-js')
<script type="text/javascript">
 $(document).ready(function(){

 })
</script>
@endsection
