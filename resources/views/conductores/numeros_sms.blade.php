@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('conductores')}}">Conductores</a></li>
          <li>Números SMS</li>
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

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                      <li>{{$error}} </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
<div class="row">

<div class="col-md-12 mb-4">
      <div class="card text-left">
          <div class="card-body">
                
                <h3 class="card-title mb3">Lista SMS</h3>
                <div class="row">

                    <div class="col-md-3 form-group mb-3">
                        <form>
                        <label><strong>Buscar Numero:</strong></label>
                        <input type="text" name="q" value="" class="form-control" >
                        <br/>
                        <button id="submit" type="submit" class="btn btn-primary">Buscar</button>
                        <a href="{{ route('conductores.admin.sms') }}" class="btn btn-danger">Cancelar</a>

                        </form>
                    </div>

                        
                    <div class="col-md-6 form-group mb-3">
                    <form action="{{route('conductores.admin.sms.save')}}" method="POST">

                        <label><strong>Nuevo Numero:</strong></label>
                        <input type="text" name="numero" value="" class="form-control" >
                        <br/>
                        <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                        <a href="{{ route('conductores.admin.sms') }}" class="btn btn-danger">Cancelar</a>
                    </form>

                    </div>
                </div>
                <!-- /.card-header -->
                <table id="hidden_column_table" class="display table table-striped table-bordered dataTable dtr-inline" style="width: 100%;" role="grid" aria-describedby="hidden_column_table_info">
                      <thead>
                        <tr>
                          <th>Celular</th>
                          <th>Whatsapp</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($numeros as $numero)
                        <tr>
                          <td>{{$numero->numero}}</td>
                          <td>{{$numero->numero}}</td>
                          <td>
                            <a class="text-danger mr-2 eliminar" href="{{route('conductores.delete.sms', ['id'=>$numero->id])}}" title="Eliminar" class=""><i class="nav-icon i-Close-Window font-weight-bold"></i></a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                      <tfoot>
                        <tr>
                        

                        </tr>
                      </tfoot>
                    </table>
             
                </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->


            <!-- /.card -->
          </div>


</div>
@endsection
@section('bottom-js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>

@endsection