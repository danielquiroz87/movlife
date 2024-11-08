

@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('vehiculos')}}">Vehiculos</a></li>
          <li>Alistamiento Diario</li>

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
            <h1>Alistamientos</h1>
            <div class="d-sm-flex mb-3" data-view="print">
            <span class="m-auto"></span>
            <a class="btn btn-success" href="{{route('alistamiento.descargar.excel')}}" target="_blank" >Descargar</a>&nbsp;&nbsp;

                    
            </div>
          </div>
  </div>

  <div class="row">

  <div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Alistamientos</h3>
              <form class="form" method="GET" >

                <div class="row">

                  <div class="col-md-2 form-group mb-4">  
                      <label>Buscar</label>
                      <input name="q" class="form-control " value="{{$q}}" ></input>
                  </div>
                
                   <div class="col-md-2 form-group mb-3">
                    <label><strong>Fecha Inicial:</strong></label>
                         <input type="date" class="form-control" name="fecha_inicial" value="{{$fecha_inicial}}" >
                  </div>

                  <div class="col-md-2 form-group mb-3">
                    <label><strong>Fecha Final:</strong></label>
                         <input type="date" class="form-control" name="fecha_final" value="{{$fecha_final}}" >
                  </div> 
                  
                  <div class="col-md-3 form-group mb-4">  
                      <label>Propietario Vehiculo</label>
                      <select name="propietario" id="propietario" class="form-control">
                      <?php echo Helper::selectPropietarios($propietario) ?>
                      </select>
                  </div>

                   <div class="col-md-2 form-group mb-4">  
                      <label>Revisado</label>
                      <select name="revisado" class="form-control">
                        <option value="-1">Todos</option>
                        <option value="1" @if($revisado==1) selected="true" @endif >Si</option>
                        <option value="0" @if($revisado==0) selected="true" @endif>No</option>
                      </select>
                  </div>

                    <div class="col-md-3 form-group mb-3">
                      <button class="btn btn-success">Buscar</button>
                  </div>
                  </div>
                </form>
              <!-- /.card-header -->
             <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                  <thead>
                    <tr>
                      <th>Id</th>
                      <th>Fecha</th>
                      <th>Placa</th>
                      <th>Conductor</th>
                      <th>Revisado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                  	@foreach ($alistamientos as $al)
                    <tr>
                      <td>{{$al->id}}</td>
                      <td>{{$al->fecha}}</td>
                      <td>{{$al->vehiculo->placa}}</td>
                      <td>{{$al->conductor->nombres}} {{$al->conductor->apellidos}}</td>
                      <td>
                        @if($al->aprobado==1)
                          Si
                        @else
                          No
                        @endif
                      </td>
                      <td>
                      @if(session::get('is_employe')==true || auth()->user()->superadmin==1 &&  $al->aprobado==0 )
                      
                      <a class="text-success mr-2" href="{{route('alistamiento.edit', $al->id)}}" title="Editar">
                        <i class="nav-icon i-Pen-2 font-weight-bold"></i>
                      </a>
                        
                      @endif

                        <a class="text-default mr-2 fuec" href="{{route('alistamiento.descargar', $al->id)}}" title="Descargar Alistamiento" target="_blank" >
                        <i class="nav-icon  i-File-Horizontal-Text font-weight-bold"></i></i></a>

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
   				    	<?php echo $alistamientos->appends(['q' => $q,
                                                    'fecha_inicial'=>$fecha_inicial,
                                                    'fecha_final'=>$fecha_final,
                                                    'revisado'=>$revisado,
                                                    'propietario'=>$propietario
                                                  ])->links(); ?>
   				    </div>

				</div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>



@endsection


@section('bottom-js')
<script>
  $('#propietario').select2({
   theme: 'bootstrap-5'
 });
</script>
@endsection