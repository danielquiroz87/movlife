@extends('layouts.master')

@section('main-content')
<style>
.rojo{
  background-color:red;
  color:white;
}
.verde{
  background-color:green;
  color:white;
}
td,th{
  font-size:9px !important ;
}
</style>
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="/#">Informes</a></li>
          <li>Documentos</li>
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
            <h1>Documentos</h1>
            <div class="d-sm-flex mb-3" data-view="print">
                  <span class="m-auto"></span>
                    <a class="btn btn-primary" id=# href="{{ url()->current().'?'.http_build_query(array_merge(request()->all(),['exportar' => true])) }}
" target="_blank" >Descargar</a>&nbsp;&nbsp;
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body" style="overflow-x:auto">
              <h3 class="card-title mb3">Lista Documentos Por Placa</h3>
              <form>
              <div class="row">

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Placa:</strong></label>
                         <input type="text" class="form-control" name="filtros[placa]" value="{{$filtros['placa']}}" >
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Tipo Vinculación:</strong></label>
                         <select name="filtros[tipo_vinculacion]" class="form-control">
                            <option>Seleccione</option>
                            <option value="0" @if($filtros['tipo_vinculacion']==0) selected="selected" @endif >Todos</option>
                            <option value="1" @if($filtros['tipo_vinculacion']==1) selected="selected" @endif >Vinculado</option>
                            <option value="806" @if($filtros['tipo_vinculacion']==806) selected="selected" @endif >Propio</option>
                         </select>
                  </div>


                  <div class="col-md-3 form-group mb-3">
                    <label>&nbsp;&nbsp;&nbsp;</label><br/>
                    <button class="btn btn-success">Filtrar</button>
                  </div>
              </div>
            </form>
              
              <!-- /.card-header -->
               
              <table class="table table-striped table-bordered dataTable dtr-inline">

                  <thead>
                    <tr>
                      <th>Placa</th>
                      <th colspan=3>TARJETA DE OPERACIÓN</th>
                      <th colspan=3>SOAT</th>
                      <th colspan=3>REVISION TECNOMECANICA</th>
                      <th colspan=3>REVISION PREVENTIVA</th>
                      <th colspan=3>POLIZA RCC</th>
                      <th colspan=3>POLIZA RCE</th>
                      <th colspan=3>POLIZA SEGURO TODO RIESGO </th>

                    </tr>
                    <tr>

                        <td>&nbsp;</td>
                        <?php for($i=1;$i<8;$i++):?>
                            <td>FIN DE VIGENCIA</td>
                            <td>FECHA EXPEDICIÓN</td>
                            <td>DIAS RESTANTES</td>
                        <?php endfor;?>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($vehiculos as $vehiculo)
                    <?php $documentos_vehiculo=Helper::getDocumentosVehiculo($vehiculo->placa);?>

                    <tr>
                      <td>{{$vehiculo->placa}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][13]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][13]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento13=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][13]['fecha_vencimiento']);
                       if(str_contains($vencimiento13,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento13}}
                      </td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][9]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][9]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento9=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][9]['fecha_vencimiento']);
                       if(str_contains($vencimiento9,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento9}}
                      </td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][10]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][10]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento10=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][10]['fecha_vencimiento']);
                       if(str_contains($vencimiento10,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento10}}
                      </td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][14]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][14]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento14=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][14]['fecha_vencimiento']);
                       if(str_contains($vencimiento14,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento14}}
                      </td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][11]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][11]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento11=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][11]['fecha_vencimiento']);
                       if(str_contains($vencimiento11,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento11}}
                      </td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][12]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][12]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento12=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][12]['fecha_vencimiento']);
                       if(str_contains($vencimiento12,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento12}}
                      </td>

                      <td>{{$documentos_vehiculo[$vehiculo->placa][30]['fecha_vencimiento']}}</td>
                      <td>{{$documentos_vehiculo[$vehiculo->placa][30]['fecha_expedicion']}}</td>
                      <td <?php $vencimiento30=Helper::getFechasDias($documentos_vehiculo[$vehiculo->placa][30]['fecha_vencimiento']);
                       if(str_contains($vencimiento30,'Vigente')){ $class='verde'; } else {$class='rojo';} ?> class="{{$class}}" >
                        {{$vencimiento30}}
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
   				    	<?php echo $vehiculos->appends(['filtros' => $filtros])->links(); ?>
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

@endsection
