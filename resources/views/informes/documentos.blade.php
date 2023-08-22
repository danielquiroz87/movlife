
@extends('layouts.master')

@section('main-content')
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
                 
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
              <h3 class="card-title mb3">Lista Documentos</h3>


                       <form>

              <div class="row">

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Tipo Vencimiento:</strong></label>
                         <select name="filtros[tipo_vencimiento]" class="form-control">
                            <option value="">Todos</option>
                            <option value="1" @if ($filtros['tipo_vencimiento']==1) selected="selected" @endif>Vencidos</option>
                             <option value="2" @if ($filtros['tipo_vencimiento']==2) selected="selected" @endif>Proximos a Vencer</option>
                         </select>
                  </div>

                  <div class="col-md-3 form-group mb-3">
                    <label><strong>Fecha Final:</strong></label>
                         <input type="date" class="form-control" name="filtros[fecha_final]" value="{{$filtros['fecha_final']}}" >
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
                      <th>Tipo Documento</th>
                      <th>Clase Registro</th>
                      <th>Nombre Conductor y/o Placa</th>
                      <th>Fecha Inicial</th>
                      <th>Fecha Final</th>
                      <th>Dias Vencimiento</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($documentos as $documento)
                    <?php
                    if($documento->dias_para_vencimiento>10){
                      $style='background-color:green;color:white;';
                    }elseif($documento->dias_para_vencimiento>=2 and $documento->dias_para_vencimiento<=10){
                          $style='background-color:yellow;color:black;';
                    }else{
                      $style='background-color:red;color:white;';
                    }
                    ?>
                    <tr>
                      <td>{{$documento->id}}</td>
                      <td>{{$documento->tipo_documento}}</td>
                      <td>{{$documento->tipo_usuario}}</td>
                      <td>{{$documento->Nombres}}</td>
                      <td>{{$documento->fecha_inicial}}</td>
                      <td>{{$documento->fecha_final}}</td>
                      <td>
                        <p  style="{{$style}} padding:2px; text-align: center;">{{$documento->dias_para_vencimiento}}</p>
                      </td>
                      <td>
                        @if($documento->tipo_usuario=='Vehiculo')
                          <a  href="{{route('vehiculos.edit', $documento->id_registro)}}">Ver</a>
                        @else
                           <a href="{{route('conductores.edit', $documento->id_registro)}}">Ver</a>
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
   				    	<?php echo $documentos->appends(['filtros' => $filtros])->links(); ?>
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
