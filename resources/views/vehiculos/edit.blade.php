@extends('layouts.master')

@section('main-content')
<div class="breadcrumb">
  <ul>
    <li><a href="/">Inicio</a></li>
    <li><a href="{{route('vehiculos')}}">Vehiculos</a></li>
    <li>Editar Vehículo</li>
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
        <h3 class="card-title mb3">Editar Vehículo</h3>

        <ul class="nav nav-tabs" id="myIconTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active show" id="general-icon-tab" data-toggle="tab" href="#general" role="tab" aria-controls="general" aria-selected="true"><i class="nav-icon i-Home1 mr-1"></i>General</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="documentos-icon-tab" data-toggle="tab" href="#documentos" role="tab" aria-controls="documentos" aria-selected="false"><i class="nav-icon i-Home1 mr-1"></i> Documentos</a>
          </li>
          <li class="nav-item"><a class="nav-link" id="historial-documentos-icon-tab" data-toggle="tab" href="#historial-documentos" role="tab" aria-controls="historial" aria-selected="false"><i class="nav-icon i-Home1 mr-1"></i> Historial Documentos</a>
          </li>
        </ul>


        <div class="tab-content">

          <div class="tab-pane fade active show" id="general" role="tabpanel" aria-labelledby="general-icon-tab">
            <div class="box box-info">
              <form action="{{route('conductores.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
                {{ csrf_field() }}
                <input type="hidden" name="id" value="{{$vehiculo->id}}">
                <input type="hidden" name="is_new" value="false">

                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Placa:</strong></label>
                    <input type="text" name="placa"  class="form-control" placeholder="XXX000" maxlength="20" required value="{{$vehiculo->placa}}">
                  </div>

                 

               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Cancelar</a>
              </div>
            </div>

          </form>

        </div>                      

      </div>

        <div class="tab-pane" id="documentos" role="tabpanel" aria-labelledby="documentos-icon-tab">
            <div class="box box-info">
                <label>Documentos Vehículo</label>
               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Cancelar</a>
              </div>
            </div>

        </div>                      

    

         <div class="tab-pane" id="historial-documentos" role="tabpanel" aria-labelledby="general-icon-tab">
            <div class="box box-info">
               
                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                    <label><strong>Historial Documentos:</strong></label>
                   
                  </div>
               <div class="col-xs-12 col-sm-12 col-md-12 ">
                <a href="{{ route('vehiculos') }}" class="btn btn-danger">Regresar a lista de Vehiculos</a>
              </div>
            </div>

          </form>

        </div>                      

      </div>



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


<script>



// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
    nombres: { required:true },
    apellidos: { required:true },
    email:{ required:true },
    documento:{ required:true },
    departamento_id:{ required:true },
    ciudad_id: { required:true },
    password:{ required:false },

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