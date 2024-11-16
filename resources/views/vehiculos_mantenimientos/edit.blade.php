@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('vehiculos.mantenimientos')}}">Vehiculos Mantenimientos</a></li>
          <li>Nuevo Mantenimiento</li>
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

    <div class="col-md-4 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <h3 class="card-title mb3">Editar Mantenimiento</h3>
  
                <div class="box box-info">
                    <form action="{{route('vehiculos.mantenimientos.save')}}" method="POST" id="form-planilla" enctype="multipart/form-data"  >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{$mt->id}}">
                    <input type="hidden" name="is_new" id="is_new" value="false">
                    
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Fecha Mantenimiento:</strong></label>
                    <input type="date" name="fecha" value="{{$mt->fecha}}"  class="form-control" placeholder="" maxlength="20" required>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Placa Vehiculo:</strong></label>
                    <input type="text"  name="placa" id="placa" value="{{$mt->placa}}" class="form-control" >
                    </div>
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Kilometros Actuales:</strong></label>
                    <input type="number"  name="kilometros" id="kilometros" value="{{$mt->kilometros}}" class="form-control" >
                    </div>
                                                
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                            <a href="{{ route('planillaservicios') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <div class="col-md-8 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <h3 class="card-title mb3">Items Mantenimiento</h3>
  
                <div class="box box-info">
                    <form action="{{route('vehiculos.mantenimientos.save')}}" method="POST" id="form-planilla" enctype="multipart/form-data"  >
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id" value="{{$mt->id}}">
                    <input type="hidden" name="is_new" id="is_new" value="false">
                    
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Fecha Mantenimiento:</strong></label>
                    <input type="date" name="fecha" value="{{$mt->fecha}}"  class="form-control" placeholder="" maxlength="20" required>
                    </div>
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Placa Vehiculo:</strong></label>
                    <input type="text"  name="placa" id="placa" value="{{$mt->placa}}" class="form-control" >
                    </div>
                    <div class="col-md-12 form-group mb-3">
                    <label><strong>Kilometros Actuales:</strong></label>
                    <input type="number"  name="kilometros" id="kilometros" value="{{$mt->kilometros}}" class="form-control" >
                    </div>
                                                
                        <div class="col-xs-12 col-sm-12 col-md-12 ">
                            <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                            <a href="{{ route('planillaservicios') }}" class="btn btn-danger">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>


</div>
@endsection
@section('bottom-js')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.js"></script>


<script>

$('#cliente_id').select2({
   theme: 'bootstrap-5'
 });

 $('#conductor_id').select2({
   theme: 'bootstrap-5'
 });

 $('#uri_sede').select2({
   theme: 'bootstrap-5'
 });

// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#form-planilla').validate({
  rules: {
        fecha: { required:true },
        cliente_id: { required:true },
        file: { required:true },
        
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



/*
$( "#submit" ).click(function(e) {
  e.preventDefault();
  if($( "#user-new-form" ).valid()){
    alert('valido');
    $( "#user-new-form" ).submit();
  }else{
    alert('ERRORES')
  }
});
*/
</script>
@endsection