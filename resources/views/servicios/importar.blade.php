@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('servicios')}}">Servicios</a></li>
          <li>Importar</li>
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>

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

<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Nueva Importación</h3>
  
 <div class="box box-info">
    <form action="{{route('servicios.importar.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="0">
      <input type="hidden" name="is_new" value="true">
      <input type="hidden" name="model" value="servicios">

        <div class="row">

           <div class="col-md-6 form-group mb-3">
              <label><strong>Archivo:</strong></label>
                   <input type="file" name="file" value="" class="form-control"  required>
            </div>

           

           
      
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('servicios') }}" class="btn btn-danger">Cancelar</a>
            </div>
        </div>

    </form>

</div>
             
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