@extends('layouts.master')

@section('main-content')
  <div class="breadcrumb">
      <ul>
          <li><a href="/">Inicio</a></li>
          <li><a href="#">Perfil</a></li>
          
      </ul>
  </div>
  <div class="separator-breadcrumb border-top"></div>
   @if ($message = Session::get('flash_message'))
    <div class="alert alert-success alert-block">
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

<div class="col-md-8 mb-4">
      <div class="card text-left">
          <div class="card-body">
                <h3 class="card-title mb3">Editar Perfil</h3>
  
 <div class="box box-info">
    <form action="{{route('perfil.save')}}" method="POST" id="user-new-form" enctype="multipart/form-data" >
    {{ csrf_field() }}
      <input type="hidden" name="id" value="{{auth()->user()->id}}">
      <input type="hidden" name="is_new" value="false">

        <div class="row">
           

            <div class="col-md-6 form-group mb-3">
                  <label><strong>Nombre</strong></label>
                  <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Nombres" required value="{{auth()->user()->name}}" disabled="true" >
            </div>
        
          
            <div class="col-md-6 form-group mb-3">
                  <label><strong>Email</strong></label>
                  <input type="text" name="nombre" class="form-control" id="email" placeholder="Nombres" required value="{{auth()->user()->email}}" disabled="true" >
            </div>
        
             <div class="col-md-6 form-group mb-3">
                  <label><strong>Nueva Contraseña</strong></label>
                  <input type="text" name="password" class="form-control" id="password" placeholder="Nueva Contraseña"  value=""  >
            </div>

             <div class="col-md-6 form-group mb-3">
                  <label><strong>Repetir Contraseña</strong></label>
                  <input type="text" name="repit_password" class="form-control" id="repit_password" placeholder="Confirmar Contraseña"  value=""  >
            </div>
        

           
            <div class="col-xs-12 col-sm-12 col-md-12 ">
                <button id="submit" type="submit" class="btn btn-primary">Enviar</button>
                <a href="{{ route('sedes') }}" class="btn btn-danger">Cancelar</a>
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
        password:{
            required: false,
            minlength: 6,
        },
        repit_password: {
            required: false,
            minlength: 6,
            equalTo: "#password"
        },messages:{
          repit_password: {
            equalTo : 'Password not matching',
          }
        }
        
        
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