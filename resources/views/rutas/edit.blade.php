@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="{{route('tarifario')}}">Rutas</a></li>
          <li>Nueva Ruta</li>
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

<div class="col-md-12">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Ruta</h3>
  
 <div class="box box-info">
    <form action="{{route('rutas.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{$ruta->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
            
             <div class="col-md-6 form-group mb-3">
              <label><strong>Código Ruta:</strong></label>
                   <input type="text" name="codigo" id="strong" value="{{$ruta->codigo}}" class="form-control" placeholder="" disabled="true" >
            </div>
            <div class="col-md-6 form-group mb-3"></div>

             <div class="col-md-6 form-group mb-3">
              <label><strong>Origen Destino:</strong></label>
                  
                   <textarea class="form-control" name="origen_destino" rows="5" >{{$ruta->origen_destino}}</textarea>
            </div>

         

            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('rutas') }}" class="btn btn-danger">Cancelar</a>
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


<script>



// just for the demos, avoids form submit
var form = $( "#user-new-form" );
$.validator.messages.required = 'Este campo es requerido';
$.validator.messages.email = 'Email invalido';

$('#user-new-form').validate({
  rules: {
        nombres: { required:true },
        apellidos: { required:true },
        departamento_id:{ required:true },
        ciudad_id: { required:true },
        
        
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